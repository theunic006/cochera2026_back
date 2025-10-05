<?php

namespace App\Http\Controllers;

use App\Models\Observacion;
use App\Http\Resources\ObservacionResource;
use App\Http\Requests\StoreObservacionRequest;
use App\Http\Requests\UpdateObservacionRequest;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;

class ObservacionController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $perPage = (int) request()->query('per_page', 15);
            $allowed = [10, 15, 20, 30, 50, 100];
            if (!in_array($perPage, $allowed)) {
                $perPage = 15;
            }

            $query = Observacion::orderBy('id', 'desc');
            $user = auth()->user();
            if ($user->idrol != 1) {
                $empresaId = $user->id_empresa ?? $user->id_company;
                $query->where('id_empresa', $empresaId);
            }
            $observaciones = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Observaciones obtenidas exitosamente',
                'data' => ObservacionResource::collection($observaciones->items()),
                'pagination' => [
                    'current_page' => $observaciones->currentPage(),
                    'last_page' => $observaciones->lastPage(),
                    'per_page' => $observaciones->perPage(),
                    'total' => $observaciones->total()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las observaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $user = \Illuminate\Support\Facades\Auth::user();
            $userId = $user ? $user->id : null;
            $empresaId = $user && $user->company ? $user->company->id : null;

            $data = $request->all();
            $data['id_user'] = $userId;
            $data['id_empresa'] = $empresaId;

            $observacion = Observacion::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Observación creada exitosamente',
                'data' => new ObservacionResource($observacion)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear observación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $observacion = Observacion::findOrFail($id);
        $user = auth()->user();
        $empresaId = $user->id_empresa ?? $user->id_company;
        if ($user->idrol != 1 && $observacion->id_empresa != $empresaId) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para ver esta observación'
            ], 403);
        }
        return new ObservacionResource($observacion);
    }

    public function update(UpdateObservacionRequest $request, $id)
    {
        $observacion = Observacion::findOrFail($id);
        $observacion->update($request->validated());
        return new ObservacionResource($observacion);
    }

    public function destroy($id)
    {
        $observacion = Observacion::findOrFail($id);
        $observacion->delete();
        return response()->json(['message' => 'Observación eliminada correctamente.']);
    }
}
