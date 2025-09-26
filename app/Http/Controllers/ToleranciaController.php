<?php

namespace App\Http\Controllers;

use App\Models\Tolerancia;
use App\Http\Requests\StoreToleranciaRequest;
use App\Http\Requests\UpdateToleranciaRequest;
use App\Http\Resources\ToleranciaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ToleranciaController extends Controller
{
    /**
     * Display a listing of tolerancias.
     * GET /api/tolerancias
     */
    public function index(Request $request)
    {
        try {
            $query = Tolerancia::query();

            // Filtrar por descripción
            if ($request->filled('descripcion')) {
                $query->where('descripcion', 'like', '%' . $request->descripcion . '%');
            }

            // Filtrar por rango de minutos
            if ($request->filled('minutos_min')) {
                $query->where('minutos', '>=', $request->minutos_min);
            }

            if ($request->filled('minutos_max')) {
                $query->where('minutos', '<=', $request->minutos_max);
            }

            // Búsqueda general
            if ($request->filled('search')) {
                $query->where('descripcion', 'like', '%' . $request->search . '%');
            }

            $tolerancias = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => ToleranciaResource::collection($tolerancias->items()),
                'pagination' => [
                    'current_page' => $tolerancias->currentPage(),
                    'per_page' => $tolerancias->perPage(),
                    'total' => $tolerancias->total(),
                    'last_page' => $tolerancias->lastPage(),
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las tolerancias',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created tolerancia.
     * POST /api/tolerancias
     */
    public function store(StoreToleranciaRequest $request)
    {
        try {
            $tolerancia = Tolerancia::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tolerancia creada exitosamente',
                'data' => new ToleranciaResource($tolerancia)
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la tolerancia',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified tolerancia.
     * GET /api/tolerancias/{id}
     */
    public function show(Tolerancia $tolerancia)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => new ToleranciaResource($tolerancia)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la tolerancia',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified tolerancia.
     * PUT /api/tolerancias/{id}
     */
    public function update(UpdateToleranciaRequest $request, Tolerancia $tolerancia)
    {
        try {
            $tolerancia->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tolerancia actualizada exitosamente',
                'data' => new ToleranciaResource($tolerancia)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la tolerancia',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified tolerancia.
     * DELETE /api/tolerancias/{id}
     */
    public function destroy(Tolerancia $tolerancia)
    {
        try {
            $tolerancia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tolerancia eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la tolerancia',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
