<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImpresionRemotaService;

class ImpresionController extends Controller
{
    /**
     * Consulta el endpoint remoto y envía los datos al servidor local para imprimir
     * GET /api/impresion/{id}
     */
    public function imprimir($id, Request $request)
    {
        $token = $request->bearerToken() ?? $request->get('token');
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token de autenticación requerido'
            ], 400);
        }
        $ticket = ImpresionRemotaService::obtenerTicketRemoto($id, $token);
        if ($ticket && isset($ticket['success']) && $ticket['success']) {
            // Enviar los datos al servidor local para imprimir
            $localUrl = 'https://semisynthetic-monophonic-bryce.ngrok-free.dev/servlocal/api/ingresos/' . $id . '/print?token=' . $token;
            $ch = curl_init($localUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $localResponse = curl_exec($ch);
            $localHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            // Puedes procesar la respuesta local si lo deseas
            return response()->json([
                'success' => true,
                'message' => 'Ticket consultado y enviado a impresión local',
                'ticket' => $ticket,
                'impresion_local' => json_decode($localResponse, true),
                'impresion_local_http' => $localHttpCode
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener el ticket remoto'
            ], 500);
        }
    }
}
