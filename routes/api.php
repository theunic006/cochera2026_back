<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuscriberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TipoVehiculoController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\VehiculoPropietarioController;
use App\Http\Controllers\ToleranciaController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\SalidaController;
use App\Http\Controllers\ObservacionController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\ImpresionController;

// ================================
// RUTAS PÚBLICAS (No requieren autenticación)
// ================================

// Rutas de suscriptores públicas
Route::prefix('suscribers')->group(function () {
    Route::get('/', [SuscriberController::class, 'index']);          // GET /api/suscribers - Listar suscriptores
    Route::post('/', [SuscriberController::class, 'store']);         // POST /api/suscribers - Crear suscriptor
});

// Ruta pública para registrar empresa sin autenticación
Route::post('/companies/register', [CompanyController::class, 'publicRegister']);   // POST /api/companies/register - Registro público de empresa

// Ruta pública para registrar usuario sin autenticación
//Route::post('/public-register', [\App\Http\Controllers\AuthController::class, 'register']);

// ================================
// AUTENTICACIÓN PÚBLICA (Solo login)
// ================================
Route::prefix('auth')->group(function () {
    // ⚠️ IMPORTANTE: Solo login es público
    // Para registrar nuevos usuarios, primero debes estar autenticado
    Route::post('/login', [AuthController::class, 'login']);          // POST /api/auth/login
        // POST /api/companies - Crear company
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

    // ================================
    // CRUD DE COMPANIES (Solo usuarios autenticados)
    // ================================
    Route::prefix('companies')->group(function () {
        // Rutas de consulta y CRUD básico
        Route::get('/', [CompanyController::class, 'index']);           // GET /api/companies - Listar companies
        Route::post('/', [CompanyController::class, 'store']);          // POST /api/companies - Crear company
        Route::get('/search', [CompanyController::class, 'search']);    // GET /api/companies/search?query=termino - Buscar companies

        // Rutas de gestión de estados
        Route::get('/statuses', [CompanyController::class, 'getAvailableStatuses']);    // GET /api/companies/statuses - Estados disponibles
        Route::get('/by-status', [CompanyController::class, 'getByStatus']);            // GET /api/companies/by-status?estado=activo - Filtrar por estado
        Route::patch('/{id}/activate', [CompanyController::class, 'activate']);         // PATCH /api/companies/{id}/activate - Activar company
        Route::patch('/{id}/suspend', [CompanyController::class, 'suspend']);           // PATCH /api/companies/{id}/suspend - Suspender company
        Route::patch('/{id}/change-status', [CompanyController::class, 'changeStatus']); // PATCH /api/companies/{id}/change-status - Cambiar estado

        // Rutas CRUD individuales
        Route::get('/{id}', [CompanyController::class, 'show']);        // GET /api/companies/{id} - Mostrar company
        Route::put('/{id}', [CompanyController::class, 'update']);      // PUT /api/companies/{id} - Actualizar company
        Route::delete('/{id}', [CompanyController::class, 'destroy']);  // DELETE /api/companies/{id} - Eliminar company
    });

    // ================================
    // CRUD DE ROLES (Solo usuarios autenticados)
    // ================================
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);              // GET /api/roles - Listar roles
        Route::post('/', [RoleController::class, 'store']);             // POST /api/roles - Crear role
        Route::get('/search', [RoleController::class, 'search']);       // GET /api/roles/search?query=termino - Buscar roles
        Route::get('/{role}', [RoleController::class, 'show']);         // GET /api/roles/{id} - Mostrar role
        Route::put('/{role}', [RoleController::class, 'update']);       // PUT /api/roles/{id} - Actualizar role
        Route::delete('/{role}', [RoleController::class, 'destroy']);   // DELETE /api/roles/{id} - Eliminar role
    });

    // ================================
    // CRUD DE TIPOS DE VEHÍCULO (Solo usuarios autenticados)
    // ================================
    Route::prefix('tipo-vehiculos')->name('tipo-vehiculos.')->group(function () {
        // Rutas de consulta y CRUD básico
        Route::get('/', [TipoVehiculoController::class, 'index']);          // GET /api/tipo-vehiculos - Listar tipos de vehículo
        Route::post('/', [TipoVehiculoController::class, 'store']);         // POST /api/tipo-vehiculos - Crear tipo de vehículo
        Route::get('/search', [TipoVehiculoController::class, 'search']);   // GET /api/tipo-vehiculos/search?query=termino - Buscar tipos de vehículo

        // Rutas de filtrado especializado
        Route::get('/con-valor', [TipoVehiculoController::class, 'conValor']);          // GET /api/tipo-vehiculos/con-valor - Solo con valor definido
        Route::get('/rango-valor', [TipoVehiculoController::class, 'porRangoValor']);   // GET /api/tipo-vehiculos/rango-valor?min=10&max=100 - Por rango de valor

        // Rutas CRUD individuales
        Route::get('/{tipo_vehiculo}', [TipoVehiculoController::class, 'show']);        // GET /api/tipo-vehiculos/{id} - Mostrar tipo de vehículo
        Route::put('/{tipo_vehiculo}', [TipoVehiculoController::class, 'update']);      // PUT /api/tipo-vehiculos/{id} - Actualizar tipo de vehículo
        Route::delete('/{tipo_vehiculo}', [TipoVehiculoController::class, 'destroy']);  // DELETE /api/tipo-vehiculos/{id} - Eliminar tipo de vehículo
    });

    // ================================
    // CRUD DE PROPIETARIOS (Solo usuarios autenticados)
    // ================================
    Route::prefix('propietarios')->name('propietarios.')->group(function () {
        Route::get('/', [PropietarioController::class, 'index']);              // GET /api/propietarios - Listar propietarios
        Route::post('/', [PropietarioController::class, 'store']);             // POST /api/propietarios - Crear propietario
        Route::get('/{propietario}', [PropietarioController::class, 'show']);  // GET /api/propietarios/{id} - Mostrar propietario
        Route::put('/{propietario}', [PropietarioController::class, 'update']); // PUT /api/propietarios/{id} - Actualizar propietario
        Route::delete('/{propietario}', [PropietarioController::class, 'destroy']); // DELETE /api/propietarios/{id} - Eliminar propietario
    });

    // ================================
    // CRUD DE VEHÍCULOS (Solo usuarios autenticados)
    // ================================
    Route::prefix('vehiculos')->name('vehiculos.')->group(function () {
        Route::get('/', [VehiculoController::class, 'index']);              // GET /api/vehiculos - Listar vehículos
        Route::post('/', [VehiculoController::class, 'store']);             // POST /api/vehiculos - Crear vehículo
        Route::get('/{vehiculo}', [VehiculoController::class, 'show']);      // GET /api/vehiculos/{id} - Mostrar vehículo
        Route::put('/{vehiculo}', [VehiculoController::class, 'update']);    // PUT /api/vehiculos/{id} - Actualizar vehículo
        Route::delete('/{vehiculo}', [VehiculoController::class, 'destroy']); // DELETE /api/vehiculos/{id} - Eliminar vehículo
    });

    // ================================
    // CRUD DE RELACIONES VEHÍCULO-PROPIETARIO (Solo usuarios autenticados)
    // ================================
    Route::prefix('vehiculo-propietarios')->name('vehiculo-propietarios.')->group(function () {
        Route::get('/', [VehiculoPropietarioController::class, 'index']);              // GET /api/vehiculo-propietarios - Listar relaciones
        Route::post('/', [VehiculoPropietarioController::class, 'store']);             // POST /api/vehiculo-propietarios - Crear relación
        Route::get('/{vehiculoPropietario}', [VehiculoPropietarioController::class, 'show']);      // GET /api/vehiculo-propietarios/{id} - Mostrar relación
        Route::put('/{vehiculoPropietario}', [VehiculoPropietarioController::class, 'update']);    // PUT /api/vehiculo-propietarios/{id} - Actualizar relación
        Route::delete('/{vehiculoPropietario}', [VehiculoPropietarioController::class, 'destroy']); // DELETE /api/vehiculo-propietarios/{id} - Eliminar relación
    });

    // ================================
    // CRUD DE TOLERANCIAS (Solo usuarios autenticados)
    // ================================
    Route::prefix('tolerancias')->name('tolerancias.')->group(function () {
        Route::get('/by-empresa', [ToleranciaController::class, 'byEmpresa']); // GET /api/tolerancias/by-empresa?id_empresa=valor - Buscar por empresa
        Route::get('/', [ToleranciaController::class, 'index']);              // GET /api/tolerancias - Listar tolerancias
        Route::post('/', [ToleranciaController::class, 'store']);             // POST /api/tolerancias - Crear tolerancia
        Route::get('/{tolerancia}', [ToleranciaController::class, 'show']);    // GET /api/tolerancias/{id} - Mostrar tolerancia
        Route::put('/{tolerancia}', [ToleranciaController::class, 'update']);  // PUT /api/tolerancias/{id} - Actualizar tolerancia
        Route::delete('/{tolerancia}', [ToleranciaController::class, 'destroy']); // DELETE /api/tolerancias/{id} - Eliminar tolerancia
        Route::get('/search', [ToleranciaController::class, 'search']); // GET /api/tolerancias/search?q=termino - Buscar tolerancias

    });

    // ================================
    // CRUD DE REGISTROS (Solo usuarios autenticados)
    // ================================
    Route::prefix('registros')->group(function () {
        Route::get('/', [RegistroController::class, 'index']);              // GET /api/registros - Listar registros
        Route::post('/', [RegistroController::class, 'store']);             // POST /api/registros - Crear registro
        Route::get('/{registro}', [RegistroController::class, 'show']);     // GET /api/registros/{id} - Mostrar registro
        Route::put('/{registro}', [RegistroController::class, 'update']);   // PUT /api/registros/{id} - Actualizar registro
        Route::delete('/{registro}', [RegistroController::class, 'destroy']); // DELETE /api/registros/{id} - Eliminar registro
    });

    // ================================
    // CRUD DE INGRESOS
    // ================================

    Route::prefix('ingresos')->group(function () {
        Route::get('/', [IngresoController::class, 'index']);              // GET /api/ingresos - Listar ingresos
        Route::post('/', [IngresoController::class, 'store']);             // POST /api/ingresos - Crear ingreso
        Route::get('/{ingreso}', [IngresoController::class, 'show']);     // GET /api/ingresos/{id} - Mostrar ingreso
        Route::put('/{ingreso}', [IngresoController::class, 'update']);   // PUT /api/ingresos/{id} - Actualizar ingreso
        Route::delete('/{ingreso}', [IngresoController::class, 'destroy']); // DELETE /api/ingresos/{id} - Eliminar ingreso
        Route::get('/{ingreso}/print', [IngresoController::class, 'printIngreso']); // GET /api/ingresos/{id}/print - Imprimir ingreso
    });

    // ================================
    // CRUD DE SALIDAS
    // ================================

    Route::prefix('salidas')->group(function () {
    Route::get('/', [SalidaController::class, 'index']);              // GET /api/salidas - Listar salidas
    Route::get('/search', [SalidaController::class, 'search']);       // GET /api/salidas/search - Buscar salidas
    Route::post('/', [SalidaController::class, 'store']);             // POST /api/salidas - Crear salida
    Route::get('/{salida}', [SalidaController::class, 'show']);     // GET /api/salidas/{id} - Mostrar salida
    });

    // ================================
    // CRUD DE OBSERVACIONES (Solo usuarios autenticados)
    // ================================
        Route::prefix('observaciones')->group(function () {
        Route::get('/', [ObservacionController::class, 'index']);              // GET /api/observaciones - Listar observaciones
        Route::post('/', [ObservacionController::class, 'store']);             // POST /api/observaciones - Crear observación
        Route::get('/{observacion}', [ObservacionController::class, 'show']);     // GET /api/observaciones/{id} - Mostrar observación
        Route::put('/{observacion}', [ObservacionController::class, 'update']);   // PUT /api/observaciones/{id} - Actualizar observación
        Route::delete('/{observacion}', [ObservacionController::class, 'destroy']); // DELETE /api/observaciones/{id} - Eliminar observación
        Route::get('/impresion/{id}', [ImpresionController::class, 'imprimir']);
    });

    // ================================
    // CRUD DE IMPRESORAS (Solo usuarios autenticados)
    // ================================
    Route::prefix('printers')->group(function () {
        Route::get('/', [PrinterController::class, 'index']);                    // GET /api/printers - Listar impresoras
        Route::get('/{printerName}', [PrinterController::class, 'show']);        // GET /api/printers/{nombre} - Mostrar impresora específica
        Route::post('/{printerName}/test', [PrinterController::class, 'test']);  // POST /api/printers/{nombre}/test - Probar impresora
    });
});
// ================================
// RUTA DE UTILIDAD (Para compatibilidad)
// ================================
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
