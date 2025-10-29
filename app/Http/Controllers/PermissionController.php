<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionController extends Controller
{
    /**
     * Listar todos los permisos
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 37);
            $module = $request->get('module');

            $query = Permission::query();

            if ($module) {
                $query->byModule($module);
            }

            $permissions = $query->orderBy('id')->orderBy('module')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Permisos obtenidos exitosamente',
                'data' => $permissions->items(),
                'pagination' => [
                    'current_page' => $permissions->currentPage(),
                    'total_pages' => $permissions->lastPage(),
                    'per_page' => $permissions->perPage(),
                    'total' => $permissions->total(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener permisos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener permisos de un usuario específico
     */
    public function getUserPermissions(string $userId): JsonResponse
    {
        try {
            $user = User::with('permissions')->findOrFail($userId);

            return response()->json([
                'success' => true,
                'message' => 'Permisos del usuario obtenidos exitosamente',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'permissions' => $user->permissions
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener permisos del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Asignar permisos a un usuario
     */
    public function assignPermissions(Request $request, string $userId): JsonResponse
    {
        try {
            $request->validate([
                'permission_ids' => 'required|array',
                'permission_ids.*' => 'exists:permissions,id'
            ]);

            $user = User::findOrFail($userId);
            $user->syncPermissions($request->permission_ids);

            return response()->json([
                'success' => true,
                'message' => 'Permisos asignados exitosamente',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'permissions' => $user->permissions()->get()
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar permisos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Agregar un permiso específico a un usuario
     */
    public function givePermission(Request $request, string $userId): JsonResponse
    {
        try {
            $request->validate([
                'permission_id' => 'required|exists:permissions,id'
            ]);

            $user = User::findOrFail($userId);
            $permission = Permission::findOrFail($request->permission_id);

            $user->givePermission($permission);

            return response()->json([
                'success' => true,
                'message' => 'Permiso otorgado exitosamente',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                    ],
                    'permission' => $permission
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario o permiso no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al otorgar permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revocar un permiso de un usuario
     */
    public function revokePermission(Request $request, string $userId): JsonResponse
    {
        try {
            $request->validate([
                'permission_id' => 'required|exists:permissions,id'
            ]);

            $user = User::findOrFail($userId);
            $permission = Permission::findOrFail($request->permission_id);

            $user->revokePermission($permission);

            return response()->json([
                'success' => true,
                'message' => 'Permiso revocado exitosamente'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario o permiso no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al revocar permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener módulos disponibles
     */
    public function getModules(): JsonResponse
    {
        try {
            $modules = Permission::select('module')
                ->distinct()
                ->whereNotNull('module')
                ->orderBy('module')
                ->pluck('module');

            return response()->json([
                'success' => true,
                'message' => 'Módulos obtenidos exitosamente',
                'data' => $modules
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener módulos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar si un usuario tiene un permiso específico
     */
    public function checkPermission(string $userId, string $permissionSlug): JsonResponse
    {
        try {
            $user = User::findOrFail($userId);
            $hasPermission = $user->hasPermission($permissionSlug);

            return response()->json([
                'success' => true,
                'message' => 'Verificación completada',
                'data' => [
                    'user_id' => $user->id,
                    'permission_slug' => $permissionSlug,
                    'has_permission' => $hasPermission
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar permiso',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
