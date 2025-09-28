<?php
namespace App\Http\Controllers;

use App\Models\Ingreso;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use App\Http\Resources\IngresoResource;


class IngresoController extends Controller
{


    /**
     * Mostrar lista de ingresos
     */
    public function index(): JsonResponse
    {
        $perPage = request()->query('per_page', 15);
        $allowed = [10, 15, 20, 30, 50, 100];
        if (!in_array((int)$perPage, $allowed)) {
            $perPage = 15;
        }
        try {
            $ingresos = Ingreso::orderBy('created_at', 'desc')->paginate($perPage);
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
            $ingreso = Ingreso::create($request->all());
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
            $ingreso = Ingreso::find($id);
            if (!$ingreso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ingreso no encontrado'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Ingreso obtenido exitosamente',
                'data' => $ingreso
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
            $ingreso = Ingreso::find($id);
            if (!$ingreso) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ingreso no encontrado'
                ], 404);
            }
            $validated = $request->all();
            $ingreso->update($validated);
            return response()->json([
                'success' => true,
                'message' => 'Ingreso actualizado exitosamente',
                'data' => $ingreso
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
            $ingreso->delete();
            return response()->json([
                'success' => true,
                'message' => 'Ingreso eliminado exitosamente'
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
