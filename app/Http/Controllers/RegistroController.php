<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreRegistroRequest;
use App\Http\Requests\UpdateRegistroRequest;
use App\Http\Resources\RegistroResource;
use Illuminate\Database\Eloquent\Casts\Json;


class RegistroController extends Controller
{
    public function index(): JsonResponse
    {
       try{
        $perPage = request()->query('per_page', 15);
            $allowed = [10, 15, 20, 30, 50, 100];
            if (!in_array((int)$perPage, $allowed)) {
                $perPage = 15;
            }
            $authUser = \Illuminate\Support\Facades\Auth::user();
            $query = Registro::query();
            if ($authUser->idrol != 1) {
                $query->where('id_empresa', $authUser->id_company);
            }
            $registros = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Registros obtenidos exitosamente',
                'data' => RegistroResource::collection($registros),
                'pagination' => [
                    'current_page' => $registros->currentPage(),
                    'last_page' => $registros->lastPage(),
                    'per_page' => $registros->perPage(),
                    'total' => $registros->total(),
                ]
            ], 200);
       }catch(\Exception $e){
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener registros',
            'error' => $e->getMessage()
        ], 500);
       }
    }

    public function store(StoreRegistroRequest $request): JsonResponse
    {
        try{
            $registro = Registro::create($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Registro creado exitosamente',
                'data' => new RegistroResource($registro)
            ], 201);
       }catch(\Exception $e){
        return response()->json([
            'success' => false,
            'message' => 'Error al crear el registro',
            'error' => $e->getMessage()
        ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try{
            $registro = Registro::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Registro obtenido exitosamente',
                'data' => new RegistroResource($registro)
            ], 200);
       }catch(\Exception $e){
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener el registro',
            'error' => $e->getMessage()
        ], 500);
        }
    }

    public function update(UpdateRegistroRequest $request, string $id): JsonResponse
    {
        try{
            $registro = Registro::findOrFail($id);
            $registro->update($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Registro actualizado exitosamente',
                'data' => new RegistroResource($registro)
            ], 200);
       }catch(\Exception $e){
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el registro',
            'error' => $e->getMessage()
        ], 500);
       }
    }


    public function destroy($id): JsonResponse
    {
        try{
            $registro = Registro::findOrFail($id);
            $registro->delete();
            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado exitosamente'
            ], 200);
       }catch(\Exception $e){
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el registro',
            'error' => $e->getMessage()
        ], 500);
        }
    }
}
