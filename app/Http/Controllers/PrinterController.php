<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PrinterController extends Controller
{
    /**
     * Listar todas las impresoras instaladas en el sistema
     */
    public function index(): JsonResponse
    {
        try {
            $printers = $this->getInstalledPrinters();

            return response()->json([
                'success' => true,
                'message' => 'Impresoras obtenidas exitosamente',
                'data' => $printers,
                'total' => count($printers)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener impresoras',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información de una impresora específica por nombre
     */
    public function show($printerName): JsonResponse
    {
        try {
            $printers = $this->getInstalledPrinters();
            $printer = collect($printers)->firstWhere('name', $printerName);

            if (!$printer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impresora no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Impresora encontrada',
                'data' => $printer
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener impresora',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Probar conexión con una impresora específica
     */
    public function test($printerName): JsonResponse
    {
        try {
            // Verificar si la impresora existe
            $printers = $this->getInstalledPrinters();
            $printer = collect($printers)->firstWhere('name', $printerName);

            if (!$printer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impresora no encontrada'
                ], 404);
            }

            // Intentar conectar y hacer una prueba de impresión
            try {
                $connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector($printerName);
                $escposPrinter = new \Mike42\Escpos\Printer($connector);

                // Enviar un texto de prueba simple
                $escposPrinter->setJustification(\Mike42\Escpos\Printer::JUSTIFY_CENTER);
                $escposPrinter->text("PRUEBA DE CONEXION\n");
                $escposPrinter->text("Cochera 2026\n");
                $escposPrinter->text("Impresora: " . $printerName . "\n");
                $escposPrinter->text("Fecha: " . date('Y-m-d H:i:s') . "\n");
                $escposPrinter->text("--------------------\n");
                $escposPrinter->text("Conexion exitosa!\n");
                $escposPrinter->feed(2);
                $escposPrinter->cut();
                $escposPrinter->close();

                return response()->json([
                    'success' => true,
                    'message' => 'Prueba de impresión exitosa',
                    'printer' => $printer
                ], 200);

            } catch (\Exception $printException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al conectar con la impresora',
                    'error' => $printException->getMessage(),
                    'printer' => $printer
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al probar impresora',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener la lista de impresoras instaladas del sistema Windows
     */
    private function getInstalledPrinters(): array
    {
        $printers = [];

        try {
            if (PHP_OS_FAMILY === 'Windows') {
                // Método 1: Usar WMI para obtener impresoras
                $command = 'wmic printer get Name,Status,Default,Local,Network,Shared /format:csv';
                $output = shell_exec($command);

                if ($output) {
                    $lines = explode("\n", trim($output));

                    // Eliminar la primera línea (cabecera vacía) y segunda línea (nombres de columnas)
                    array_shift($lines); // Elimina línea vacía
                    $headers = array_shift($lines); // Obtiene cabeceras

                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (empty($line)) continue;

                        $fields = str_getcsv($line);
                        if (count($fields) >= 6) {
                            $printers[] = [
                                'name' => trim($fields[4]) ?: 'Sin nombre',
                                'status' => trim($fields[5]) ?: 'Desconocido',
                                'is_default' => (trim($fields[1]) === 'TRUE'),
                                'is_local' => (trim($fields[2]) === 'TRUE'),
                                'is_network' => (trim($fields[3]) === 'TRUE'),
                                'is_shared' => (trim($fields[6]) === 'TRUE'),
                                'type' => 'Windows Printer'
                            ];
                        }
                    }
                }

                // Método 2: Fallback usando PowerShell si WMI falla
                if (empty($printers)) {
                    $psCommand = 'powershell "Get-Printer | Select-Object Name, DriverName, PortName, Shared, Published, Type | ConvertTo-Json"';
                    $psOutput = shell_exec($psCommand);

                    if ($psOutput) {
                        $jsonData = json_decode($psOutput, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            // Si es un solo elemento, convertir a array
                            if (!isset($jsonData[0])) {
                                $jsonData = [$jsonData];
                            }

                            foreach ($jsonData as $printer) {
                                $printers[] = [
                                    'name' => $printer['Name'] ?? 'Sin nombre',
                                    'driver' => $printer['DriverName'] ?? 'Sin driver',
                                    'port' => $printer['PortName'] ?? 'Sin puerto',
                                    'is_shared' => $printer['Shared'] ?? false,
                                    'is_published' => $printer['Published'] ?? false,
                                    'type' => $printer['Type'] ?? 'Local',
                                    'status' => 'Disponible'
                                ];
                            }
                        }
                    }
                }

                // Método 3: Fallback básico si los anteriores fallan
                if (empty($printers)) {
                    $basicCommand = 'wmic printer get name';
                    $basicOutput = shell_exec($basicCommand);

                    if ($basicOutput) {
                        $lines = explode("\n", trim($basicOutput));
                        array_shift($lines); // Eliminar cabecera "Name"

                        foreach ($lines as $line) {
                            $printerName = trim($line);
                            if (!empty($printerName)) {
                                $printers[] = [
                                    'name' => $printerName,
                                    'status' => 'Disponible',
                                    'type' => 'Windows Printer'
                                ];
                            }
                        }
                    }
                }

            } else {
                // Para sistemas Linux/Unix
                $command = 'lpstat -p';
                $output = shell_exec($command);

                if ($output) {
                    $lines = explode("\n", trim($output));
                    foreach ($lines as $line) {
                        if (preg_match('/printer\s+(.+?)\s+/', $line, $matches)) {
                            $printers[] = [
                                'name' => $matches[1],
                                'status' => 'Disponible',
                                'type' => 'CUPS Printer'
                            ];
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            // Si todo falla, devolver al menos algunas impresoras comunes para testing
            $printers = [
                [
                    'name' => 'Microsoft Print to PDF',
                    'status' => 'Disponible',
                    'type' => 'Virtual Printer',
                    'is_default' => false
                ],
                [
                    'name' => 'Microsoft XPS Document Writer',
                    'status' => 'Disponible',
                    'type' => 'Virtual Printer',
                    'is_default' => false
                ]
            ];
        }

        return $printers;
    }
}
