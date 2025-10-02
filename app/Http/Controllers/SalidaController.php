<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\SalidaResource;
use App\Http\Requests\StoreSalidaRequest;
use App\Http\Requests\UpdateSalidaRequest;

class SalidaController extends Controller
{
    // Listar salidas
    public function index(): JsonResponse
    {
        $salidas = Salida::with(['ingreso', 'user', 'empresa'])->orderByDesc('fecha_salida')->paginate(20);
        return response()->json([
            'success' => true,
            'data' => SalidaResource::collection($salidas)
        ]);
    }

    // Crear salida
    public function store(StoreSalidaRequest $request): JsonResponse
    {
        $salida = Salida::create($request->validated());
        return response()->json([
            'success' => true,
            'data' => new SalidaResource($salida)
        ], 201);
    }

    // Mostrar salida específica
    public function show($id): JsonResponse
    {
        $salida = Salida::with(['ingreso', 'user', 'empresa'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => new SalidaResource($salida)
        ]);
    }
}
