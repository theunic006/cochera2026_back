<?php

namespace App\Http\Controllers\Facturacion;

use App\Http\Controllers\Controller;
use App\Models\Facturacion\ComprobanteDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComprobanteDetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $detalles = ComprobanteDetalle::orderBy('id', 'desc')->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Detalles de comprobantes obtenidos exitosamente',
                'data' => $detalles->items(),
                'current_page' => $detalles->currentPage(),
                'per_page' => $detalles->perPage(),
                'total' => $detalles->total(),
                'last_page' => $detalles->lastPage(),
                'from' => $detalles->firstItem(),
                'to' => $detalles->lastItem()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalles de comprobantes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_comprobante' => 'required|exists:factura2026.comprobantes,id',
                'id_producto' => 'nullable|integer',
                'item' => 'required|integer',
                'codigo_producto' => 'nullable|string|max:20',
                'descripcion' => 'required|string|max:250',
                'familia' => 'nullable|string|max:100',
                'unidad_medida' => 'nullable|string|max:3',
                'cantidad' => 'required|numeric|min:0',
                'precio_unitario' => 'required|numeric|min:0',
                'valor_unitario' => 'nullable|numeric|min:0',
                'descuento' => 'nullable|numeric|min:0',
                'subtotal' => 'required|numeric|min:0',
                'igv' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'tipo_igv' => 'nullable|string|max:2',
                'placa' => 'nullable|string|max:20',
                'orden_compra' => 'nullable|string|max:50',
                'numero_contrato' => 'nullable|string|max:50',
                'numero_guia' => 'nullable|string|max:50',
                'observaciones' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detalle = ComprobanteDetalle::create($request->all());
            $detalle->load(['comprobante']);

            return response()->json([
                'success' => true,
                'message' => 'Detalle de comprobante creado exitosamente',
                'data' => $detalle
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear detalle de comprobante',
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
            $detalle = ComprobanteDetalle::with(['comprobante'])->find($id);

            if (!$detalle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detalle de comprobante no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detalle de comprobante obtenido exitosamente',
                'data' => $detalle
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalle de comprobante',
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
            $detalle = ComprobanteDetalle::find($id);

            if (!$detalle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detalle de comprobante no encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'id_comprobante' => 'sometimes|required|exists:factura2026.comprobantes,id',
                'id_producto' => 'nullable|integer',
                'item' => 'sometimes|required|integer',
                'codigo_producto' => 'nullable|string|max:20',
                'descripcion' => 'sometimes|required|string|max:250',
                'familia' => 'nullable|string|max:100',
                'unidad_medida' => 'nullable|string|max:3',
                'cantidad' => 'sometimes|required|numeric|min:0',
                'precio_unitario' => 'sometimes|required|numeric|min:0',
                'valor_unitario' => 'nullable|numeric|min:0',
                'descuento' => 'nullable|numeric|min:0',
                'subtotal' => 'sometimes|required|numeric|min:0',
                'igv' => 'sometimes|required|numeric|min:0',
                'total' => 'sometimes|required|numeric|min:0',
                'tipo_igv' => 'nullable|string|max:2',
                'placa' => 'nullable|string|max:20',
                'orden_compra' => 'nullable|string|max:50',
                'numero_contrato' => 'nullable|string|max:50',
                'numero_guia' => 'nullable|string|max:50',
                'observaciones' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detalle->update($request->all());
            $detalle->load(['comprobante']);

            return response()->json([
                'success' => true,
                'message' => 'Detalle de comprobante actualizado exitosamente',
                'data' => $detalle
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar detalle de comprobante',
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
            $detalle = ComprobanteDetalle::find($id);

            if (!$detalle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detalle de comprobante no encontrado'
                ], 404);
            }

            $detalle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Detalle de comprobante eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar detalle de comprobante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener detalles por ID de comprobante.
     */
    public function porComprobante(string $idComprobante)
    {
        try {
            $detalles = ComprobanteDetalle::where('id_comprobante', $idComprobante)
                ->orderBy('item', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Detalles del comprobante obtenidos exitosamente',
                'data' => $detalles,
                'total' => $detalles->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalles del comprobante',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
