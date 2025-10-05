<?php

namespace App\Http\Controllers;

use App\Models\Salida;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\SalidaResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSalidaRequest;
use App\Http\Requests\UpdateSalidaRequest;

class SalidaController extends Controller
{
    // Buscar salidas por fecha, usuario o empresa
    public function search(Request $request): JsonResponse
    {
        $query = Salida::with(['registro', 'user', 'empresa']);
        $user = auth()->user();
        if ($user->idrol != 1) {
            $empresaId = $user->id_empresa ?? $user->id_company;
            $query->where('id_empresa', $empresaId);
        }

        if ($request->filled('fecha')) {
            $query->where('fecha_salida', $request->input('fecha'));
        }
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->input('id_user'));
        }
        if ($request->filled('id_empresa')) {
            $query->where('id_empresa', $request->input('id_empresa'));
        }
        // Filtro por placa
        if ($request->filled('placa')) {
            $query->whereHas('registro.vehiculo', function($q) use ($request) {
                $q->where('placa', 'like', '%' . $request->input('placa') . '%');
            });
        }
        // Filtro por tipo_vehiculo
        if ($request->filled('tipo_vehiculo')) {
            $query->whereHas('registro.vehiculo', function($q) use ($request) {
                $q->where('tipo_vehiculo_id', $request->input('tipo_vehiculo'));
            });
        }
        // Filtro por tipo_pago
        if ($request->filled('tipo_pago')) {
            $query->where('tipo_pago', $request->input('tipo_pago'));
        }

        $salidas = $query->orderByDesc('fecha_salida')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => SalidaResource::collection($salidas)
        ]);
    }
    // Listar salidas
    public function index(): JsonResponse
    {

          $query = Salida::with(['registro', 'user', 'empresa']);
    $user = auth()->user();
    if ($user->idrol != 1) {
        $empresaId = $user->id_empresa ?? $user->id_company;
        $query->where('id_empresa', $empresaId);
    }
    $salidas = $query->orderByDesc('fecha_salida')->paginate(20);
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
        $salida = Salida::with(['registro', 'user', 'empresa'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => new SalidaResource($salida)
        ]);
    }
}
