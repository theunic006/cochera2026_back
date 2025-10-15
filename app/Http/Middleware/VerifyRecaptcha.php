<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RecaptchaService;
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptcha
{
    protected $recaptchaService;

    public function __construct(RecaptchaService $recaptchaService)
    {
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $action = null, float $scoreThreshold = 0.5): Response
    {
        // Si reCAPTCHA no está habilitado, permitir la solicitud
        if (!$this->recaptchaService->isEnabled()) {
            return $next($request);
        }

        $recaptchaToken = $request->input('recaptcha_token');

        // Verificar que el token existe
        if (!$recaptchaToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token de verificación de seguridad requerido',
                'error' => 'recaptcha_token_missing'
            ], 422);
        }

        // Verificar el token con Google
        $result = $this->recaptchaService->verify($recaptchaToken, $action, $scoreThreshold);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Verificación de seguridad fallida. Por favor, intenta nuevamente.',
                'error' => 'recaptcha_verification_failed',
                'details' => $result['error'] ?? 'Unknown error',
                'score' => $result['score'] ?? 0
            ], 422);
        }

        // Si llegamos aquí, la verificación fue exitosa
        // Agregar información de reCAPTCHA al request para uso posterior
        $request->merge([
            'recaptcha_verified' => true,
            'recaptcha_score' => $result['score'],
            'recaptcha_action' => $result['action']
        ]);

        return $next($request);
    }
}
