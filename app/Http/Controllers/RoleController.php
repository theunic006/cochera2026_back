<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/roles
     */
    public function index(): JsonResponse
    {
        try {
            $perPage = request()->query('per_page', 15);
            $allowed = [10, 15, 20, 30, 50, 100];
            if (!in_array((int)$perPage, $allowed)) {
                $perPage = 15;
            }
            // Obtener roles con conteo de usuarios, excluyendo el id 1 (SUPERADMINISTRADOR)
            $roles = Role::withCount('users')
                        ->where('id', '!=', 1)
                        ->orderBy('id', 'desc')
                        ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Roles obtenidos exitosamente',
                'data' => RoleResource::collection($roles->items()),
                'pagination' => [
                    'current_page' => $roles->currentPage(),
                    'last_page' => $roles->lastPage(),
                    'per_page' => $roles->perPage(),
                    'total' => $roles->total()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/roles
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        try {
            $role = Role::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Role creado exitosamente',
                'data' => new RoleResource($role)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/roles/{id}
     */
    public function show(string $id): JsonResponse
    {
        try {
            if ($id == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role no encontrado'
                ], 404);
            }
            $role = Role::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Role obtenido exitosamente',
                'data' => new RoleResource($role)
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/roles/{id}
     */
    public function update(UpdateRoleRequest $request, string $id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            $role->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Role actualizado exitosamente',
                'data' => new RoleResource($role->fresh())
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/roles/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role eliminado exitosamente'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el role',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search roles by description
     * GET /api/roles/search?query=termino
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('query', '');

            if (empty($query)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Se requiere un término de búsqueda'
                ], 400);
            }

            $roles = Role::where('descripcion', 'LIKE', "%{$query}%")
                        ->orderBy('descripcion', 'asc')
                        ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => "Roles encontrados para: '{$query}'",
                'data' => RoleResource::collection($roles->items()),
                'pagination' => [
                    'current_page' => $roles->currentPage(),
                    'last_page' => $roles->lastPage(),
                    'per_page' => $roles->perPage(),
                    'total' => $roles->total()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
