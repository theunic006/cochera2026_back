<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuscriberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// ================================
// RUTAS PÚBLICAS (No requieren autenticación)
// ================================

// Rutas de suscriptores públicas
Route::prefix('suscribers')->group(function () {
    Route::get('/', [SuscriberController::class, 'index']);          // GET /api/suscribers - Listar suscriptores
    Route::post('/', [SuscriberController::class, 'store']);         // POST /api/suscribers - Crear suscriptor
});

// ================================
// AUTENTICACIÓN PÚBLICA (Solo login)
// ================================
Route::prefix('auth')->group(function () {
    // ⚠️ IMPORTANTE: Solo login es público
    // Para registrar nuevos usuarios, primero debes estar autenticado
    Route::post('/login', [AuthController::class, 'login']);          // POST /api/auth/login
});

// ================================
// AUTENTICACIÓN CON SESIONES TRADICIONALES
// ================================
Route::prefix('auth')->middleware([
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
])->group(function () {
    // Estas rutas usan la tabla 'sessions' en lugar de tokens
    Route::post('/session-login', [AuthController::class, 'sessionLogin']);    // POST /api/auth/session-login
    Route::post('/session-logout', [AuthController::class, 'sessionLogout']);  // POST /api/auth/session-logout
    Route::get('/session-user', [AuthController::class, 'sessionUser']);       // GET /api/auth/session-user
    Route::get('/test-session', [AuthController::class, 'testSession']);       // GET /api/auth/test-session (debug)
});

// ================================
// 🔒 RUTAS PROTEGIDAS (Requieren autenticación con token)
// ================================
Route::middleware('auth:sanctum')->group(function () {

    // ================================
    // GESTIÓN DE AUTENTICACIÓN Y PERFIL
    // ================================
    Route::prefix('auth')->group(function () {
        // 👤 REGISTRO DE NUEVOS USUARIOS (Solo usuarios autenticados pueden crear otros usuarios)
        Route::post('/register', [AuthController::class, 'register']);              // POST /api/auth/register - ⚠️ PROTEGIDO

        // 🚪 GESTIÓN DE SESIONES
        Route::post('/logout', [AuthController::class, 'logout']);                    // POST /api/auth/logout
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);            // POST /api/auth/logout-all

        // 👤 GESTIÓN DE PERFIL
        Route::get('/profile', [AuthController::class, 'profile']);                  // GET /api/auth/profile
        Route::put('/profile', [AuthController::class, 'updateProfile']);            // PUT /api/auth/profile
        Route::post('/change-password', [AuthController::class, 'changePassword']);  // POST /api/auth/change-password

        // 🔍 VERIFICACIÓN Y MONITOREO
        Route::get('/verify-token', [AuthController::class, 'verifyToken']);         // GET /api/auth/verify-token
        Route::get('/active-sessions', [AuthController::class, 'activeSessions']);   // GET /api/auth/active-sessions
    });

    // ================================
    // CRUD DE USUARIOS (Solo usuarios autenticados)
    // ================================
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);           // GET /api/users - Listar usuarios
        Route::post('/', [UserController::class, 'store']);          // POST /api/users - Crear usuario (alternativa a register)
        Route::get('/search', [UserController::class, 'search']);    // GET /api/users/search?q=termino - Buscar usuarios
        Route::get('/{id}', [UserController::class, 'show']);        // GET /api/users/{id} - Mostrar usuario
        Route::put('/{id}', [UserController::class, 'update']);      // PUT /api/users/{id} - Actualizar usuario
        Route::delete('/{id}', [UserController::class, 'destroy']);  // DELETE /api/users/{id} - Eliminar usuario
    });
});
// ================================
// RUTA DE UTILIDAD (Para compatibilidad)
// ================================
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
