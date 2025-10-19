<?php
namespace App\Services;

class ImpresionRemotaService
{
    /**
     * Consulta el endpoint remoto y retorna el JSON
     * @param int|string $id
     * @param string $token
     * @return array|null
     */
    public static function obtenerTicketRemoto($id, $token)
    {
        $url = "http://api.garage-peru.shop/api/ingresos/{$id}/print";
        $headers = [
            "Authorization: Bearer {$token}",
            "Accept: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $json = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $json;
            }
        }
        return null;
    }
}
