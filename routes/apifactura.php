<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Facturacion\EmpresaController;
use App\Http\Controllers\Facturacion\ClienteController;
use App\Http\Controllers\Facturacion\SerieController;
use App\Http\Controllers\Facturacion\ComprobanteController;
use App\Http\Controllers\Facturacion\ComprobanteDetalleController;

/*
|--------------------------------------------------------------------------
| API Routes - Facturación
|--------------------------------------------------------------------------
|
| Aquí están registradas todas las rutas de la API para el sistema de
| facturación. Estas rutas utilizan la base de datos factura2026 de manera
| independiente.
|
*/

// Ruta de prueba sin autenticación
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API de Facturación funcionando correctamente',
        'database' => 'factura2026',
        'endpoints' => [
            'empresas' => '/apifactura/empresas',
            'clientes' => '/apifactura/clientes',
            'series' => '/apifactura/series',
            'comprobantes' => '/apifactura/comprobantes',
        ]
    ]);
});

Route::middleware('auth:sanctum')->group(function () {

    // Rutas para Empresas
    Route::apiResource('empresas', EmpresaController::class);

    // Rutas para Clientes
    Route::post('clientes/buscar-documento', [ClienteController::class, 'buscarPorDocumento']);
    Route::apiResource('clientes', ClienteController::class);

    // Rutas para Series
    Route::apiResource('series', SerieController::class);

    // Rutas para Comprobantes
    Route::apiResource('comprobantes', ComprobanteController::class);

    // Rutas para Comprobante Detalle
    Route::get('comprobantes/{id}/detalles', [ComprobanteDetalleController::class, 'porComprobante']);
    Route::apiResource('comprobante-detalles', ComprobanteDetalleController::class);
});
