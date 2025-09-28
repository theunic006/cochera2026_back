<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Http\Resources\VehiculoResource;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\DB;
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
     * Store a newly created resource in storage.
     */
    public function store(StoreVehiculoRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Establecer tipo_vehiculo_id = 1 si está vacío o es null
        if (empty($data['tipo_vehiculo_id'])) {
            $data['tipo_vehiculo_id'] = 1;
        }

        DB::beginTransaction();
        try {
            // Buscar si la placa ya existe
            $vehiculo = Vehiculo::where('placa', $data['placa'])->first();

            $comentario = '';
            if (!$vehiculo) {
                // Si no existe, crear el vehículo
                $vehiculo = Vehiculo::create($data);
                $vehiculo->load('tipoVehiculo');
                $vehiculoCreated = true;
            } else {
                $vehiculoCreated = false;
            }

            // Verificar si ya existe ingreso para el vehículo
            $existeIngreso = \App\Models\Ingreso::where('id_vehiculo', $vehiculo->id)->exists();
            if (!$vehiculo && !$existeIngreso) {
                $comentario = 'No existe placa: se guardó en vehiculos, ingresos y registros.';
            } elseif ($vehiculo && !$existeIngreso) {
                $comentario = 'Existe placa: se guardó en ingresos y registros.';
            } elseif ($vehiculo && $existeIngreso) {
                $comentario = 'Ya existe ingreso: solo se guardó en registros.';
            }

            if (!$existeIngreso) {
                $ingreso = \App\Models\Ingreso::create([
                    'fecha_ingreso' => now()->toDateString(),
                    'hora_ingreso' => now()->format('H:i:s'),
                    'id_user' => $data['id_user'] ?? 1,
                    'id_empresa' => $data['id_empresa'] ?? 1,
                    'id_vehiculo' => $vehiculo->id,
                ]);
            } else {
                $ingreso = null;
            }

            // Crear registro relacionado
            $registro = \App\Models\Registro::create([
                'id_vehiculo' => $vehiculo->id,
                'id_user' => $data['id_user'] ?? 1,
                'id_empresa' => $data['id_empresa'] ?? 1,
                'fecha' => now(),
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $vehiculoCreated
                    ? 'Vehículo, ingreso y registro creados exitosamente'
                    : ($ingreso ? 'Ingreso y registro creados exitosamente (vehículo ya existe)' : 'Solo registro creado (vehículo e ingreso ya existen)'),
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
     * Remove the specified resource from storage.
     */
    public function destroy(Vehiculo $vehiculo): JsonResponse
    {
        $vehiculo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehículo eliminado exitosamente'
        ]);
    }
}
