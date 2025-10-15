<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Http\Resources\VehiculoResource;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Vehiculo::with('tipoVehiculo');
        $empresaId = \Illuminate\Support\Facades\Auth::user()->id_empresa ?? \Illuminate\Support\Facades\Auth::user()->id_company;
        if (\Illuminate\Support\Facades\Auth::user()->idrol != 1) {
            $query->where('id_empresa', $empresaId);
        }

        // Filtros
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('placa', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%");
            });
        }

        if ($request->has('placa')) {
            $query->where('placa', 'like', '%' . $request->get('placa') . '%');
        }

        if ($request->has('tipo_vehiculo_id')) {
            $query->where('tipo_vehiculo_id', $request->get('tipo_vehiculo_id'));
        }

        if ($request->has('marca')) {
            $query->where('marca', 'like', '%' . $request->get('marca') . '%');
        }

        $perPage = $request->get('per_page', 15);
        $allowed = [10, 15, 20, 30, 50, 100];
        if (!in_array((int)$perPage, $allowed)) {
            $perPage = 15;
        }
        $vehiculos = $query->orderBy('placa')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => VehiculoResource::collection($vehiculos->items()),
            'pagination' => [
                'current_page' => $vehiculos->currentPage(),
                'per_page' => $vehiculos->perPage(),
                'total' => $vehiculos->total(),
                'last_page' => $vehiculos->lastPage(),
            ]
        ]);
    }

    /**
     * INGRESAR NUEVO VEHÍCULO AL SISTEMA
     */
    public function store(StoreVehiculoRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (empty($data['tipo_vehiculo_id'])) {
            $data['tipo_vehiculo_id'] = 1;
        }
        // Asignar id_empresa automáticamente según el usuario autenticado
        $data['id_empresa'] = \Illuminate\Support\Facades\Auth::user()->id_company;

        DB::beginTransaction();
        try {
            // Buscar si la placa ya existe

            $vehiculo = Vehiculo::where('placa', $data['placa'])->where('id_empresa', $data['id_empresa'])->first();
            $vehiculoCreated = false;
            $comentario = '';
            $ingreso = null;
            $registro = null;

            if (!$vehiculo) {
                $data['frecuencia'] = 1;
                $vehiculo = Vehiculo::create($data);
                $vehiculoCreated = true;
            } else {
                $vehiculo->frecuencia = ($vehiculo->frecuencia ?? 0) + 1;
                $vehiculo->save();
            }

            $vehiculo->load('tipoVehiculo');

            // Verificar si ya existe ingreso para el vehículo
            $existeIngreso = \App\Models\Ingreso::where('id_vehiculo', $vehiculo->id)->exists();
                if ($existeIngreso) {
                    $mensaje = 'Ya existe La placa.';
                    DB::commit();
                    return response()->json([
                        'success' => true,
                        'message' => $mensaje,
                        'vehiculo' => new VehiculoResource($vehiculo),
                        'ingreso' => null,
                        'registro' => null
                    ], 200);
            }

            // Si no existe ingreso, guardar ingreso y registro
            $user = Auth::user();
            $userId = $user ? $user->id : null;
            $empresaId = $user && $user->company ? $user->company->id : null;

            $ingreso = \App\Models\Ingreso::create([
                'fecha_ingreso' => now()->toDateString(),
                'hora_ingreso' => now()->format('H:i:s'),
                'id_user' => $userId,
                'id_empresa' => $empresaId,
                'id_vehiculo' => $vehiculo->id,
            ]);
            // Si tienes lógica para registro, descomenta y ajusta aquí
            // $registro = ...

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $vehiculoCreated
                    ? 'Vehículo, ingreso y registro creados exitosamente'
                    : 'Placa Registada',
                'comentario' => $comentario,
                'vehiculo' => new VehiculoResource($vehiculo),
                'ingreso' => $ingreso,
                'registro' => $registro
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear vehículo, ingreso o registro',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehiculoRequest $request, Vehiculo $vehiculo): JsonResponse
    {
        $data = $request->validated();

        // Establecer tipo_vehiculo_id = 1 si está vacío o es null
        if (empty($data['tipo_vehiculo_id'])) {
            $data['tipo_vehiculo_id'] = 1;
        }

        $vehiculo->update($data);
        $vehiculo->load('tipoVehiculo');

        return response()->json([
            'success' => true,
            'message' => 'Vehículo actualizado exitosamente',
            'data' => new VehiculoResource($vehiculo)
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Vehiculo $vehiculo): JsonResponse
    {
        $vehiculo->load('tipoVehiculo');

        return response()->json([
            'success' => true,
            'data' => new VehiculoResource($vehiculo)
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehiculo $vehiculo): JsonResponse
    {
        // Verificar si existen ingresos asociados a este vehículo
        $tieneIngresos = \App\Models\Ingreso::where('id_vehiculo', $vehiculo->id)->exists();
        if ($tieneIngresos) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar: existen ingresos asociados a este vehículo.'
            ], 409);
        }
        $vehiculo->delete();
        return response()->json([
            'success' => true,
            'message' => 'Vehículo eliminado exitosamente'
        ]);
    }
}
