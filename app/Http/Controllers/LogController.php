<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LogController extends Controller
{
    // Listar logs
    public function index(Request $request): JsonResponse
    {
        $logs = Log::with(['user', 'empresa'])->orderByDesc('fecha')->paginate(20);
        return response()->json(['success' => true, 'data' => $logs]);
    }

    // Crear log
    public function store(Request $request): JsonResponse
    {
        $data = $request->only([
            'accion', 'id_user', 'id_empresa', 'ip', 'detalle', 'estado', 'user_agent'
        ]);
        $data['fecha'] = now();
        $log = Log::create($data);
        return response()->json(['success' => true, 'data' => $log], 201);
    }

    // Mostrar log específico
    public function show($id): JsonResponse
    {
        $log = Log::with(['user', 'empresa'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $log]);
    }

    // Actualizar log
    public function update(Request $request, $id): JsonResponse
    {
        $log = Log::findOrFail($id);
        $log->update($request->only([
            'accion', 'id_user', 'id_empresa', 'ip', 'detalle', 'estado', 'user_agent'
        ]));
        return response()->json(['success' => true, 'data' => $log]);
    }

    // Eliminar log
    public function destroy($id): JsonResponse
    {
        $log = Log::findOrFail($id);
        $log->delete();
        return response()->json(['success' => true, 'message' => 'Log eliminado']);
    }
}
