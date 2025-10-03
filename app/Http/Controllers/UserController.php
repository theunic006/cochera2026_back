<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Mostrar lista de usuarios
     */
    public function index(): JsonResponse
    {
        try {
            $perPage = request()->query('per_page', 15);
            $allowed = [10, 15, 20, 30, 50, 100];
            if (!in_array((int)$perPage, $allowed)) {
                $perPage = 15;
            }
            $users = User::with(['role', 'company'])->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Usuarios obtenidos exitosamente',
                'data' => UserResource::collection($users),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'idrol' => $validatedData['idrol'] ?? null,
                'id_company' => $validatedData['id_company'] ?? null,
                'estado' => $validatedData['estado'] ?? 'ACTIVO',
            ]);

            // Cargar las relaciones para mostrar en la respuesta
            $user->load(['role', 'company']);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => new UserResource($user)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un usuario específico
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = User::with(['role', 'company'])->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Usuario obtenido exitosamente',
                'data' => new UserResource($user)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un usuario específico
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $validated = $request->validated();

            // Actualizar solo los campos que se enviaron
            if (isset($validated['name'])) {
                $user->name = $validated['name'];
            }

            if (isset($validated['email'])) {
                $user->email = $validated['email'];
            }

            if (isset($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }


            if (isset($validated['idrol'])) {
                $user->idrol = $validated['idrol'];
            }

            if (isset($validated['id_company'])) {
                $user->id_company = $validated['id_company'];
            }

            if (isset($validated['estado'])) {
                $user->estado = $validated['estado'];
            }

            $user->save();

            // Cargar las relaciones para mostrar en la respuesta
            $user->load(['role', 'company']);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente',
                'data' => new UserResource($user)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un usuario específico
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar usuarios por nombre o email
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $search = $request->query('q');

            if (!$search) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetro de búsqueda requerido'
                ], 400);
            }

            $users = User::where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Búsqueda completada exitosamente',
                'data' => UserResource::collection($users),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
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
