<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para comunicarse con el servidor local de impresión
 * 
 * Este servicio se encarga de notificar al servidor local (XAMPP)
 * cuando se necesita imprimir un ticket automáticamente.
 */
class LocalPrinterService
{
    /**
     * URL del servidor local de impresión
     * @var string
     */
    protected $serverUrl;

    /**
     * Constructor
     */
    public function __construct()
    {
        // URL del servidor local (ajustar según tu configuración)
        $this->serverUrl = config('services.local_printer.url', 'http://localhost/servlocal/api-handler.php');
    }

    /**
     * Notificar al servidor local para que consulte e imprima
     * 
     * @param int $ingresoId ID del ingreso a imprimir
     * @return array Respuesta del servidor local
     */
    public function notificarImpresion($ingresoId)
    {
        try {
            // Hacer petición GET al servidor local
            $response = Http::timeout(2) // Timeout de 2 segundos
                ->get($this->serverUrl, [
                    'action' => 'fetch-and-print',
                    'id' => $ingresoId
                ]);

            // Si la petición fue exitosa
            if ($response->successful()) {
                $data = $response->json();
                
                Log::info("Impresión notificada correctamente", [
                    'ingreso_id' => $ingresoId,
                    'respuesta' => $data
                ]);

                return [
                    'success' => true,
                    'message' => 'Notificación enviada al servidor de impresión',
                    'printer_response' => $data
                ];
            }

            // Si hubo error en la respuesta
            Log::warning("Error al notificar impresión", [
                'ingreso_id' => $ingresoId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Error al comunicarse con el servidor de impresión',
                'error' => $response->body()
            ];

        } catch (\Exception $e) {
            // Si hay error de conexión, no queremos que falle la respuesta principal
            Log::error("Excepción al notificar impresión", [
                'ingreso_id' => $ingresoId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'No se pudo conectar con el servidor de impresión',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Enviar datos directamente al servidor local para imprimir
     * (Método alternativo que envía todos los datos)
     * 
     * @param array $datosIngreso Datos completos del ingreso
     * @return array Respuesta del servidor local
     */
    public function imprimirDirecto($datosIngreso)
    {
        try {
            $response = Http::timeout(2)
                ->post($this->serverUrl . '?action=print-ingreso', $datosIngreso);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Impresión enviada correctamente',
                    'printer_response' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Error al imprimir',
                'error' => $response->body()
            ];

        } catch (\Exception $e) {
            Log::error("Error al imprimir directamente", [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error de conexión con impresora',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verificar si el servidor local está disponible
     * 
     * @return bool
     */
    public function isAvailable()
    {
        try {
            $response = Http::timeout(1)
                ->get($this->serverUrl, ['action' => 'estado']);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
