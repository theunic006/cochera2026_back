<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Http\Resources\VehiculoResource;
use App\Models\Vehiculo;
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

        $vehiculos = $query->orderBy('placa')->paginate(15);

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
        $vehiculo = Vehiculo::create($request->validated());
        $vehiculo->load('tipoVehiculo');

        return response()->json([
            'success' => true,
            'message' => 'Vehículo creado exitosamente',
            'data' => new VehiculoResource($vehiculo)
        ], 201);
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
        $vehiculo->update($request->validated());
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
