<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'data' => [
                    'user' => new UserResource($user),
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Iniciar sesión
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'data' => [
                    'user' => new UserResource($user),
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revocar el token actual
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cerrar todas las sesiones
     */
    public function logoutAll(Request $request): JsonResponse
    {
        try {
            // Revocar todos los tokens del usuario
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Todas las sesiones han sido cerradas exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar todas las sesiones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener perfil del usuario autenticado
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Perfil obtenido exitosamente',
                'data' => new UserResource($request->user())
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar perfil del usuario autenticado
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'current_password' => 'required_with:password|string',
                'password' => 'sometimes|required|string|min:8|different:current_password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar contraseña actual si se quiere cambiar la contraseña
            if ($request->has('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La contraseña actual es incorrecta'
                    ], 401);
                }
            }

            // Actualizar campos
            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
                $user->email_verified_at = null; // Reset verification if email changes
            }

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado exitosamente',
                'data' => new UserResource($user)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|different:current_password',
                'new_password_confirmation' => 'required|same:new_password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 401);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            // Opcional: cerrar todas las sesiones excepto la actual
            $currentTokenId = $request->user()->currentAccessToken()->id;
            $request->user()->tokens()->where('id', '!=', $currentTokenId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar contraseña',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar si el token es válido
     */
    public function verifyToken(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Token válido',
            'data' => [
                'user' => new UserResource($request->user()),
                'token_valid' => true
            ]
        ], 200);
    }

    // ================================
    // MÉTODOS PARA SESIONES TRADICIONALES
    // ================================

    /**
     * Iniciar sesión con sesiones tradicionales (almacena en tabla sessions)
     */
    public function sessionLogin(Request $request): JsonResponse
    {
        try {
            // Debug: Verificar si la sesión está disponible
            if (!$request->hasSession()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesión no disponible',
                    'error' => 'El middleware de sesiones no está activo'
                ], 500);
            }

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $remember = $request->boolean('remember', false);

            // Intentar autenticación
            if (Auth::attempt($request->only('email', 'password'), $remember)) {
                // Regenerar ID de sesión por seguridad
                $request->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'data' => [
                        'user' => new UserResource(Auth::user()),
                        'session_id' => $request->session()->getId(),
                        'remember' => $remember,
                        'session_driver' => config('session.driver')
                    ]
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar sesión',
                'error' => $e->getMessage(),
                'debug' => [
                    'session_driver' => config('session.driver'),
                    'has_session' => $request->hasSession(),
                    'session_started' => $request->session() ? 'true' : 'false'
                ]
            ], 500);
        }
    }    /**
     * Cerrar sesión con sesiones tradicionales
     */
    public function sessionLogout(Request $request): JsonResponse
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener usuario autenticado por sesión
     */
    public function sessionUser(Request $request): JsonResponse
    {
        try {
            if (Auth::check()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario autenticado',
                    'data' => [
                        'user' => new UserResource(Auth::user()),
                        'session_id' => $request->session()->getId(),
                        'authenticated' => true
                    ]
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
                'data' => [
                    'authenticated' => false
                ]
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar sesiones activas del usuario
     */
    public function activeSessions(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Para Sanctum tokens
            $tokens = $user->tokens()->get()->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'last_used_at' => $token->last_used_at?->format('Y-m-d H:i:s'),
                    'created_at' => $token->created_at->format('Y-m-d H:i:s'),
                    'type' => 'token'
                ];
            });

            // Para sesiones de base de datos
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'last_activity' => date('Y-m-d H:i:s', $session->last_activity),
                        'type' => 'session'
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Sesiones activas obtenidas',
                'data' => [
                    'tokens' => $tokens,
                    'sessions' => $sessions,
                    'total_tokens' => $tokens->count(),
                    'total_sessions' => $sessions->count()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener sesiones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método de prueba para verificar configuración de sesiones
     */
    public function testSession(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Información de sesiones',
                'data' => [
                    'session_driver' => config('session.driver'),
                    'session_table' => config('session.table'),
                    'session_lifetime' => config('session.lifetime'),
                    'has_session' => $request->hasSession(),
                    'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                    'middleware_loaded' => class_exists(\Illuminate\Session\Middleware\StartSession::class),
                    'session_config' => [
                        'connection' => config('session.connection'),
                        'cookie' => config('session.cookie'),
                        'domain' => config('session.domain'),
                        'secure' => config('session.secure'),
                        'http_only' => config('session.http_only'),
                        'same_site' => config('session.same_site'),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar sesiones',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
