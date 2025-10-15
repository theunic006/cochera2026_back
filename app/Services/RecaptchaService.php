<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private $client;
    private $secretKey;

    public function __construct()
    {
        $this->client = new Client();
        // Clave secreta de reCAPTCHA (debe estar en .env)
        $this->secretKey = env('RECAPTCHA_SECRET_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
    }

    /**
     * Verificar token de reCAPTCHA con Google
     *
     * @param string $token
     * @param string|null $action
     * @param float $scoreThreshold
     * @return array
     */
    public function verify(string $token, string $action = null, float $scoreThreshold = 0.5): array
    {
        try {
            $response = $this->client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $this->secretKey,
                    'response' => $token,
                    'remoteip' => request()->ip(),
                ]
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            if (!$body) {
                return [
                    'success' => false,
                    'error' => 'Invalid response from reCAPTCHA API',
                    'score' => 0
                ];
            }

            // Log para debug
            Log::info('reCAPTCHA verification result:', [
                'success' => $body['success'] ?? false,
                'score' => $body['score'] ?? 0,
                'action' => $body['action'] ?? null,
                'hostname' => $body['hostname'] ?? null,
                'challenge_ts' => $body['challenge_ts'] ?? null,
                'error_codes' => $body['error-codes'] ?? []
            ]);

            // Verificar que la verificación fue exitosa
            if (!($body['success'] ?? false)) {
                return [
                    'success' => false,
                    'error' => 'reCAPTCHA verification failed',
                    'score' => 0,
                    'error_codes' => $body['error-codes'] ?? []
                ];
            }

            // Para reCAPTCHA v3, verificar el score
            $score = $body['score'] ?? 0;
            if ($score < $scoreThreshold) {
                return [
                    'success' => false,
                    'error' => 'reCAPTCHA score too low (possible bot)',
                    'score' => $score
                ];
            }

            // Verificar acción si se proporciona
            if ($action && isset($body['action']) && $body['action'] !== $action) {
                return [
                    'success' => false,
                    'error' => 'reCAPTCHA action mismatch',
                    'score' => $score,
                    'expected_action' => $action,
                    'received_action' => $body['action']
                ];
            }

            return [
                'success' => true,
                'score' => $score,
                'action' => $body['action'] ?? null,
                'hostname' => $body['hostname'] ?? null
            ];

        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'reCAPTCHA service unavailable',
                'score' => 0
            ];
        }
    }

    /**
     * Verificar si reCAPTCHA está habilitado
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return !empty($this->secretKey) && $this->secretKey !== '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
    }
}
