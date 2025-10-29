<?php

namespace App\Http\Controllers\Facturacion;

use App\Http\Controllers\Controller;
use App\Models\Facturacion\Comprobante;
use App\Models\Facturacion\ComprobanteDetalle;
use App\Models\Facturacion\Payload;
use App\Models\Facturacion\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $comprobantes = Comprobante::with(['cliente', 'empresa'])
                ->orderBy('fecha_emision', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Comprobantes obtenidos exitosamente',
                'data' => $comprobantes->items(),
                'current_page' => $comprobantes->currentPage(),
                'per_page' => $comprobantes->perPage(),
                'total' => $comprobantes->total(),
                'last_page' => $comprobantes->lastPage(),
                'from' => $comprobantes->firstItem(),
                'to' => $comprobantes->lastItem()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener comprobantes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::connection('factura2026')->beginTransaction();

        try {
            // Validar datos principales
            $validator = Validator::make($request->all(), [
                'id_empresa' => 'required|exists:factura2026.empresas,id',
                'cliente_numero_de_documento' => 'required|string',
                'serie' => 'required|string|max:4',
                'numero' => 'required|integer',
                'fecha_de_emision' => 'required|date',
                'moneda' => 'required|string|max:3',
                'items' => 'required|array|min:1',
                'items.*.descripcion' => 'required|string',
                'items.*.cantidad' => 'required|numeric|min:0',
                'items.*.valor_unitario' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buscar o crear cliente
            $cliente = Cliente::where('numero_documento', $request->cliente_numero_de_documento)->first();

            if (!$cliente) {
                $cliente = Cliente::create([
                    'tipo_documento' => $request->cliente_tipo_de_documento == '6' ? 'RUC' : 'DNI',
                    'numero_documento' => $request->cliente_numero_de_documento,
                    'razon_social' => $request->cliente_denominacion,
                    'direccion' => $request->cliente_direccion ?? null,
                    'estado' => 1
                ]);
            }

            // Mapear tipo de documento
            $tipoComprobante = '01'; // Factura por defecto
            if ($request->documento == 'boleta') $tipoComprobante = '03';
            if ($request->documento == 'nota_credito') $tipoComprobante = '07';
            if ($request->documento == 'nota_debito') $tipoComprobante = '08';

            // Crear comprobante
            $comprobante = Comprobante::create([
                'id_empresa' => $request->id_empresa,
                'id_cliente' => $cliente->id,
                'tipo_comprobante' => $tipoComprobante,
                'serie' => $request->serie,
                'correlativo' => str_pad($request->numero, 8, '0', STR_PAD_LEFT),
                'fecha_emision' => $request->fecha_de_emision,
                'fecha_vencimiento' => $request->fecha_de_vencimiento ?? $request->fecha_de_emision,
                'moneda' => $request->moneda,
                'tipo_operacion' => $request->tipo_operacion ?? '0101',
                'gravadas' => $request->total_gravada ?? 0,
                'monto_subtotal' => $request->total_gravada ?? 0,
                'igv' => $request->total_igv ?? 0,
                'total' => $request->total,
                'estado' => 'EMITIDO',
                'condicion_pago' => $request->condicion_pago ?? 'CONTADO',
                'observaciones' => null,
            ]);

            // Crear detalles (items)
            foreach ($request->items as $index => $item) {
                $cantidad = floatval($item['cantidad']);
                $valorUnitario = floatval($item['valor_unitario']);
                $porcentajeIgv = floatval($item['porcentaje_igv'] ?? 18);

                $subtotal = $cantidad * $valorUnitario;
                $igv = $subtotal * ($porcentajeIgv / 100);
                $total = $subtotal + $igv;

                ComprobanteDetalle::create([
                    'id_comprobante' => $comprobante->id,
                    'item' => $index + 1,
                    'codigo_producto' => $item['codigo_producto'] ?? null,
                    'descripcion' => $item['descripcion'],
                    'unidad_medida' => $item['unidad_de_medida'] ?? 'NIU',
                    'cantidad' => $cantidad,
                    'precio_unitario' => $valorUnitario * (1 + ($porcentajeIgv / 100)),
                    'valor_unitario' => $valorUnitario,
                    'descuento' => 0,
                    'subtotal' => $subtotal,
                    'igv' => $igv,
                    'total' => $total,
                    'tipo_igv' => $item['codigo_tipo_afectacion_igv'] ?? '10',
                ]);
            }

            // Guardar payload de SUNAT si viene en el request
            if ($request->has('payload')) {
                $payloadWrapper = $request->input('payload');

                // El payload real está en payload.payload
                $payloadData = $payloadWrapper['payload'] ?? $payloadWrapper;

                if ($payloadData && is_array($payloadData) && isset($payloadData['estado'])) {
                    Payload::create([
                        'id_comprobante' => $comprobante->id,
                        'estado' => $payloadData['estado'],
                        'hash' => $payloadData['hash'] ?? null,
                        'xml' => $payloadData['xml'] ?? null,
                        'cdr' => $payloadData['cdr'] ?? null,
                        'ticket' => $payloadData['pdf']['ticket'] ?? null,
                    ]);

                    // Actualizar estado del comprobante según respuesta de SUNAT
                    $comprobante->update(['estado' => $payloadData['estado']]);
                }
            }

            DB::connection('factura2026')->commit();            $comprobante->load(['cliente', 'empresa', 'detalles', 'payload']);

            return response()->json([
                'success' => true,
                'message' => 'Comprobante creado exitosamente',
                'data' => $comprobante
            ], 201);

        } catch (\Exception $e) {
            DB::connection('factura2026')->rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear comprobante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $comprobante = Comprobante::with(['cliente', 'empresa'])->find($id);

            if (!$comprobante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comprobante no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Comprobante obtenido exitosamente',
                'data' => $comprobante
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener comprobante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $comprobante = Comprobante::find($id);

            if (!$comprobante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comprobante no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'id_empresa' => 'sometimes|required|exists:factura2026.empresas,id',
                'id_cliente' => 'sometimes|required|exists:factura2026.clientes,id',
                'tipo_comprobante' => 'sometimes|required|string|max:2',
                'serie' => 'sometimes|required|string|max:4',
                'correlativo' => 'sometimes|required|string',
                'fecha_emision' => 'sometimes|required|date',
                'fecha_vencimiento' => 'nullable|date',
                'condicion_pago' => 'nullable|string|max:50',
                'moneda' => 'sometimes|required|string|max:3',
                'tipo_operacion' => 'nullable|string|max:4',
                'gravadas' => 'nullable|numeric|min:0',
                'exoneradas' => 'nullable|numeric|min:0',
                'inafectas' => 'nullable|numeric|min:0',
                'operaciones_gratuitas' => 'nullable|numeric|min:0',
                'operaciones_exportadas' => 'nullable|numeric|min:0',
                'igv' => 'nullable|numeric|min:0',
                'total' => 'sometimes|required|numeric|min:0',
                'estado' => 'nullable|string|max:20',
                'observaciones' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $comprobante->update($request->all());
            $comprobante->load(['cliente', 'empresa']);

            return response()->json([
                'success' => true,
                'message' => 'Comprobante actualizado exitosamente',
                'data' => $comprobante
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar comprobante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $comprobante = Comprobante::find($id);

            if (!$comprobante) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comprobante no encontrado'
                ], 404);
            }

            $comprobante->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comprobante eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar comprobante',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
