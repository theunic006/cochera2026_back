<?php

namespace App\Http\Controllers\Facturacion;

use App\Http\Controllers\Controller;
use App\Models\Facturacion\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SerieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $series = Serie::with(['empresa'])->orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Series obtenidas exitosamente',
                'data' => $series,
                'total' => $series->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener series',
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
                'id_empresa' => 'required|exists:factura2026.empresas,id',
                'tipo_comprobante' => 'required|string|max:50',
                'serie' => 'required|string|max:10',
                'correlativo_actual' => 'nullable|integer|min:0',
                'estado' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $serie = Serie::create($request->all());
            $serie->load('empresa');

            return response()->json([
                'success' => true,
                'message' => 'Serie creada exitosamente',
                'data' => $serie
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear serie',
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
            $serie = Serie::with(['empresa', 'comprobantes'])->find($id);

            if (!$serie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Serie no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Serie obtenida exitosamente',
                'data' => $serie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener serie',
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
            $serie = Serie::find($id);

            if (!$serie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Serie no encontrada'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'id_empresa' => 'sometimes|required|exists:factura2026.empresas,id',
                'tipo_comprobante' => 'sometimes|required|string|max:50',
                'serie' => 'sometimes|required|string|max:10',
                'correlativo_actual' => 'nullable|integer|min:0',
                'estado' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $serie->update($request->all());
            $serie->load('empresa');

            return response()->json([
                'success' => true,
                'message' => 'Serie actualizada exitosamente',
                'data' => $serie
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar serie',
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
            $serie = Serie::find($id);

            if (!$serie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Serie no encontrada'
                ], 404);
            }

            $serie->delete();

            return response()->json([
                'success' => true,
                'message' => 'Serie eliminada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar serie',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
