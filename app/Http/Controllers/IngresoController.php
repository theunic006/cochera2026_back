<?php
namespace App\Http\Controllers;

use App\Models\Ingreso;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use App\Http\Resources\IngresoResource;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Mike42\Escpos\EscposImage;


class IngresoController extends Controller
{
    /**
     * Imprimir ticket de ingreso en impresora térmica
     */
    public function printIngreso($id)
    {
        try {
            $ingreso = \App\Models\Ingreso::with(['vehiculo.tipoVehiculo'])->find($id);
            if (!$ingreso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ingreso no encontrado'
                ], 404);
            }

            // Requiere mike42/escpos-php instalado
            // composer require mike42/escpos-php
            try {
                $connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector("T20"); // Cambia T20 por el nombre de tu impresora
                $printer = new \Mike42\Escpos\Printer($connector);

                $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_CENTER);
                $printer->text("COCHERA 2026\n");
                $printer->text("-----------------------------\n");
                $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_LEFT);
                $printer->text("Placa: " . ($ingreso->vehiculo->placa ?? '-') . "\n");
                $printer->text("Fecha Ingreso: " . ($ingreso->fecha_ingreso ?? '-') . "\n");
                $printer->text("Hora Ingreso: " . ($ingreso->hora_ingreso ?? '-') . "\n");
                $printer->text("Tipo Vehiculo: " . ($ingreso->vehiculo->tipoVehiculo->nombre ?? '-') . "\n");
                $valor = $ingreso->vehiculo->tipoVehiculo->valor ?? '-';
                $printer->text("Valor hora Fraccion: S/. " . (is_numeric($valor) ? number_format($valor, 2) : $valor) . "\n");
                $printer->text("-----------------------------\n");
                // Imprimir código de barras de la placa
                // Imprimir código de barras de la placa usando el método nativo
                $placa = $ingreso->vehiculo->placa ?? null;
                if ($placa) {
                    $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_CENTER);
                    $printer->text("\n");
                    $printer->setBarcodeHeight(60); // altura aprox. 1cm
                    $printer->setBarcodeWidth(3);   // ancho estándar
                    $printer->barcode($placa, \Mike42\Escpos\Printer::BARCODE_CODE39);
                    $printer->feed();
                } else {
                    $printer->text("[No se pudo imprimir el código de barras]\n");
                }
                $printer->text("¡Gracias por su visita!\n");
                $printer->feed(2);
                $printer->cut();
                $printer->close();
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al imprimir',
                    'error' => $e->getMessage()
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ticket impreso correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al imprimir ingreso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        /**
     * Registrar un nuevo ingreso
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = \Illuminate\Support\Facades\Auth::user();
            $userId = $user ? $user->id : null;
            $companyId = $user && $user->company ? $user->company->id : null;

            $data = $request->all();
            $data['id_user'] = $userId;
            $data['id_company'] = $companyId;


            $ingreso = Ingreso::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Ingreso creado exitosamente',
                'data' => new IngresoResource($ingreso)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear ingreso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un ingreso específico
     */
    public function show(string $id): JsonResponse
    {
        try {
            $ingreso = Ingreso::with(['user', 'vehiculo.tipoVehiculo'])->find($id);
            if (!$ingreso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ingreso no encontrado'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Ingreso obtenido exitosamente',
                'data' => new IngresoResource($ingreso)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener ingreso',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Actualizar un ingreso específico
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $ingreso = Ingreso::with('vehiculo')->find($id);
            if (!$ingreso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ingreso no encontrado'
                ], 404);
            }
            $data = $request->all();

            // Actualizar ingreso (fecha y hora)
            if (isset($data['fecha_ingreso'])) {
                $ingreso->fecha_ingreso = $data['fecha_ingreso'];
            }
            if (isset($data['hora_ingreso'])) {
                $ingreso->hora_ingreso = $data['hora_ingreso'];
            }
            $ingreso->save();

            // Actualizar datos del vehículo relacionado
            $vehiculo = $ingreso->vehiculo;
            if ($vehiculo) {
                if (isset($data['vehiculo']['placa'])) {
                    $vehiculo->placa = $data['vehiculo']['placa'];
                }
                if (isset($data['vehiculo']['tipo_vehiculo_id'])) {
                    $vehiculo->tipo_vehiculo_id = $data['vehiculo']['tipo_vehiculo_id'];
                }
                $vehiculo->save();
            }

            // Guardar observación si viene en el request
            if (isset($data['observacion']) && is_array($data['observacion'])) {
                $obsData = $data['observacion'];
                // Solo guardar si la descripción no está vacía
                if (!empty($obsData['descripcion'])) {
                    $user = \Illuminate\Support\Facades\Auth::user();
                    if ($user) {
                        $obsData['id_usuario'] = $user->id;
                        $obsData['id_empresa'] = $user->company->id ?? null;
                    }
                    \App\Models\Observacion::create($obsData);
                }
            }

            // Recargar relaciones para la respuesta
            $ingreso->load(['user', 'vehiculo.tipoVehiculo', 'vehiculo.observaciones']);

            return response()->json([
                'success' => true,
                'message' => 'Ingreso actualizado exitosamente',
                'data' => new IngresoResource($ingreso)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar ingreso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un ingreso específico
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $ingreso = Ingreso::find($id);
            if (!$ingreso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ingreso no encontrado'
                ], 404);
            }

            // Guardar registro antes de eliminar, incluyendo fecha/hora ingreso
            $placa = $ingreso->vehiculo ? $ingreso->vehiculo->placa : null;
            $usuario = $ingreso->user ? $ingreso->user->name : null;
            $registro = \App\Models\Registro::create([
                'id_vehiculo' => $ingreso->id_vehiculo,
                'id_user' => $ingreso->id_user,
                'id_empresa' => $ingreso->id_empresa,
                'placa' => $placa,
                'user' => $usuario,
                'fecha' => now(),
                'fecha_ingreso' => $ingreso->fecha_ingreso,
                'hora_ingreso' => $ingreso->hora_ingreso,
            ]);
            $nuevoIdRegistro = $registro->id;

            // Calcular tiempo de permanencia
            $fechaIngreso = $ingreso->fecha_ingreso;
            $horaIngreso = $ingreso->hora_ingreso;
            $fechaSalida = now()->toDateString();
            $horaSalida = now()->format('H:i:s');

            $entrada = \Carbon\Carbon::parse("$fechaIngreso $horaIngreso");
            $salida = \Carbon\Carbon::parse("$fechaSalida $horaSalida");
            $diff = $entrada->diff($salida);
            $tiempo = $diff->format('%H:%I:%S');

            // Permitir sobreescribir tiempo y precio desde el body
            $body = request()->all();
            $tiempoSalida = $body['tiempo'] ?? $tiempo;
            $precioSalida = $body['precio'] ?? 3;
            $tipoPago = $body['tipo_pago'] ?? null;

            // Guardar salida
            $salidaModel = \App\Models\Salida::create([
                'placa' => $placa,
                'user' => $usuario,
                'fecha_salida' => $fechaSalida,
                'hora_salida' => $horaSalida,
                'tiempo' => $tiempoSalida,
                'precio' => $precioSalida,
                'tipo_pago' => $tipoPago,
                'id_registro' => $nuevoIdRegistro,
                'id_user' => $ingreso->id_user,
                'id_empresa' => $ingreso->id_empresa,
            ]);

            $ingreso->delete();
            return response()->json([
                'success' => true,
                'message' => 'Ingreso eliminado, registro y salida creados exitosamente',
                'registro' => $registro,
                'salida' => $salidaModel
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar ingreso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar ingresos por fecha_ingreso
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $search = $request->query('q');
            if (!$search) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetro de búsqueda requerido'
                ], 400);
            }
            $ingresos = Ingreso::where('fecha_ingreso', 'LIKE', "%{$search}%")
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
            return response()->json([
                'success' => true,
                'message' => 'Búsqueda completada exitosamente',
                'data' => $ingresos->items(),
                'pagination' => [
                    'current_page' => $ingresos->currentPage(),
                    'last_page' => $ingresos->lastPage(),
                    'per_page' => $ingresos->perPage(),
                    'total' => $ingresos->total(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
