<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // ...
        });
    }

    /**
     * Personalizar respuesta para autenticación fallida en API
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'success' => false,
            'message' => 'No autenticado. Debe enviar un token de acceso válido en el encabezado Authorization (Bearer <token>).',
            'error_code' => 'AUTH_401',
            'hint' => 'Solicite un token válido mediante el endpoint de login o registro.'
        ], 401);
    }
}
