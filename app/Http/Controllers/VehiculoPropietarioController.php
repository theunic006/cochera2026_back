<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Vehiculo;
use App\Models\Propietario;
use App\Http\Resources\VehiculoResource;
use App\Http\Resources\PropietarioResource;
use Illuminate\Validation\ValidationException;

class VehiculoPropietarioController extends Controller
{
    /**
     * Obtener propietarios de un vehículo
     */
    public function index(Request $request): JsonResponse
    {
        $vehiculoId = $request->query('vehiculo_id');
        $propietarioId = $request->query('propietario_id');

        if ($vehiculoId) {
            // Obtener propietarios de un vehículo específico
            $vehiculo = Vehiculo::findOrFail($vehiculoId);
            $propietarios = $vehiculo->propietarios()->get();

            return response()->json([
                'success' => true,
                'message' => "Propietarios del vehículo {$vehiculo->placa}",
                'data' => [
                    'vehiculo' => new VehiculoResource($vehiculo),
                    'propietarios' => PropietarioResource::collection($propietarios)
                ]
            ]);
        }

        if ($propietarioId) {
            // Obtener vehículos de un propietario específico
            $propietario = Propietario::findOrFail($propietarioId);
            $vehiculos = $propietario->vehiculos()->get();

            return response()->json([
                'success' => true,
                'message' => "Vehículos del propietario {$propietario->nombre}",
                'data' => [
                    'propietario' => new PropietarioResource($propietario),
                    'vehiculos' => VehiculoResource::collection($vehiculos)
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Debe especificar vehiculo_id o propietario_id'
        ], 400);
    }

    /**
     * Asignar propietario a vehículo
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'vehiculo_id' => 'required|integer|exists:vehiculos,id',
            'propietario_id' => 'required|integer|exists:propietarios,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
        ]);

        $vehiculo = Vehiculo::findOrFail($request->vehiculo_id);
        $propietario = Propietario::findOrFail($request->propietario_id);

        // Verificar si ya existe la relación
        if ($vehiculo->propietarios()->where('propietario_id', $request->propietario_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'El propietario ya está asignado a este vehículo'
            ], 422);
        }

        // Asignar propietario al vehículo
        $pivotData = [];
        if ($request->fecha_inicio) {
            $pivotData['fecha_inicio'] = $request->fecha_inicio;
        }
        if ($request->fecha_fin) {
            $pivotData['fecha_fin'] = $request->fecha_fin;
        }

        $vehiculo->propietarios()->attach($request->propietario_id, $pivotData);

        return response()->json([
            'success' => true,
            'message' => "Propietario {$propietario->nombre} asignado al vehículo {$vehiculo->placa}",
            'data' => [
                'vehiculo' => new VehiculoResource($vehiculo->load('propietarios')),
                'propietario' => new PropietarioResource($propietario)
            ]
        ], 201);
    }

    /**
     * Mostrar relación específica vehículo-propietario
     */
    public function show(string $id): JsonResponse
    {
        // Implementar si es necesario mostrar detalles de una relación específica
        return response()->json([
            'success' => false,
            'message' => 'Método no implementado'
        ], 501);
    }

    /**
     * Actualizar fechas de la relación vehículo-propietario
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'vehiculo_id' => 'required|integer|exists:vehiculos,id',
            'propietario_id' => 'required|integer|exists:propietarios,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
        ]);

        $vehiculo = Vehiculo::findOrFail($request->vehiculo_id);

        // Verificar si existe la relación
        if (!$vehiculo->propietarios()->where('propietario_id', $request->propietario_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'La relación vehículo-propietario no existe'
            ], 404);
        }

        // Actualizar datos del pivot
        $pivotData = [];
        if ($request->has('fecha_inicio')) {
            $pivotData['fecha_inicio'] = $request->fecha_inicio;
        }
        if ($request->has('fecha_fin')) {
            $pivotData['fecha_fin'] = $request->fecha_fin;
        }

        $vehiculo->propietarios()->updateExistingPivot($request->propietario_id, $pivotData);

        return response()->json([
            'success' => true,
            'message' => 'Relación actualizada exitosamente',
            'data' => new VehiculoResource($vehiculo->load('propietarios'))
        ]);
    }

    /**
     * Eliminar relación vehículo-propietario
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'vehiculo_id' => 'required|integer|exists:vehiculos,id',
            'propietario_id' => 'required|integer|exists:propietarios,id',
        ]);

        $vehiculo = Vehiculo::findOrFail($request->vehiculo_id);
        $propietario = Propietario::findOrFail($request->propietario_id);

        // Verificar si existe la relación
        if (!$vehiculo->propietarios()->where('propietario_id', $request->propietario_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'La relación vehículo-propietario no existe'
            ], 404);
        }

        // Eliminar la relación
        $vehiculo->propietarios()->detach($request->propietario_id);

        return response()->json([
            'success' => true,
            'message' => "Propietario {$propietario->nombre} desasignado del vehículo {$vehiculo->placa}"
        ]);
    }
}
