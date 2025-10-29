<?php

namespace App\Http\Controllers\Facturacion;

use App\Http\Controllers\Controller;
use App\Models\Facturacion\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $empresas = Empresa::orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Empresas obtenidas exitosamente',
                'data' => $empresas,
                'total' => $empresas->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener empresas',
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
                'ruc' => 'required|string|max:11|unique:factura2026.empresas,ruc',
                'razon_social' => 'required|string|max:255',
                'nombre_comercial' => 'nullable|string|max:255',
                'direccion' => 'nullable|string|max:500',
                'telefono' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'logo' => 'nullable|string|max:500',
                'usuario_sol' => 'nullable|string|max:50',
                'clave_sol' => 'nullable|string|max:100',
                'certificado_digital' => 'nullable|string',
                'estado' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $empresa = Empresa::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Empresa creada exitosamente',
                'data' => $empresa
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear empresa',
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
            $empresa = Empresa::with(['series'])->find($id);

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Empresa obtenida exitosamente',
                'data' => $empresa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener empresa',
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
            $empresa = Empresa::find($id);

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no encontrada'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'ruc' => 'sometimes|required|string|max:11|unique:factura2026.empresas,ruc,' . $id,
                'razon_social' => 'sometimes|required|string|max:255',
                'nombre_comercial' => 'nullable|string|max:255',
                'direccion' => 'nullable|string|max:500',
                'telefono' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'logo' => 'nullable|string|max:500',
                'usuario_sol' => 'nullable|string|max:50',
                'clave_sol' => 'nullable|string|max:100',
                'certificado_digital' => 'nullable|string',
                'estado' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $empresa->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Empresa actualizada exitosamente',
                'data' => $empresa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar empresa',
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
            $empresa = Empresa::find($id);

            if (!$empresa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empresa no encontrada'
                ], 404);
            }

            $empresa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Empresa eliminada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
