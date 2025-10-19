<?php
namespace App\Http\Controllers;

use App\Models\Ingreso;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\IngresoResource;
// ...existing code...
// ...existing code...
class IngresoController extends Controller{
    /**
     * Imprimir ticket de ingreso en impresora térmica
     */


    /**
     * Imprimir ticket de ingreso en impresora térmica
     */
    public function index(): JsonResponse
    {
        $perPage = request()->query('per_page', 15);
        $allowed = [10, 15, 20, 30, 50, 100];
        if (!in_array((int)$perPage, $allowed)) {
            $perPage = 15;
        }
        try {
            $authUser = \Illuminate\Support\Facades\Auth::user();
            $query = Ingreso::with(['vehiculo.tipoVehiculo', 'observaciones']);
            if ($authUser->idrol != 1) {
                $query->where('id_empresa', $authUser->id_company);
            }
            // Filtro por placa
            $search = request()->query('search');
            if (!empty($search)) {
                $query->whereHas('vehiculo', function($q) use ($search) {
                    $q->where('placa', 'LIKE', $search . '%');
                });
            }
            $ingresos = $query->orderBy('created_at', 'desc')->paginate($perPage);
            return response()->json([
                'success' => true,
                'message' => 'Ingresos obtenidos exitosamente',
                'data' => IngresoResource::collection($ingresos),
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
                'message' => 'Error al obtener ingresos',
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
            $perPage = $request->query('per_page', 15); // Valor por defecto 15
            if (!$search) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetro de búsqueda requerido'
                ], 400);
            }
            $ingresos = Ingreso::where('fecha_ingreso', 'LIKE', "%{$search}%")
                        ->orderBy('created_at', 'desc')
                        ->paginate($perPage);
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

    public function printIngreso($id): \Illuminate\Http\JsonResponse
    {
        $ingreso = \App\Models\Ingreso::with(['vehiculo.tipoVehiculo'])->find($id);
        if (!$ingreso) {
            return response()->json([
                'success' => false,
                'message' => 'Ingreso no encontrado'
            ], 404);
        }
        // Preparar datos del ticket para la respuesta
        $vehiculo = $ingreso->vehiculo;
        $ticketData = [
            'placa' => $vehiculo->placa ?? null,
            'fecha_ingreso' => $ingreso->fecha_ingreso ?? null,
            'hora_ingreso' => $ingreso->hora_ingreso ?? null,
            'tipo_vehiculo' => $vehiculo && $vehiculo->tipoVehiculo ? $vehiculo->tipoVehiculo->nombre : null,
            'valor_hora_fraccion' => $vehiculo && $vehiculo->tipoVehiculo ? (is_numeric($vehiculo->tipoVehiculo->valor) ? number_format($vehiculo->tipoVehiculo->valor, 2) : $vehiculo->tipoVehiculo->valor) : null,
        ];

        // Obtener el token del request si existe
        $token = request()->bearerToken();


        return response()->json([
            'success' => true,
            'message' => 'Ticket generado correctamente',
            'data' => $ticketData,
            'token' => $token,
        ], 200);
    }

}
