<?php

namespace App\Http\Controllers;

use App\Models\Tolerancia;
use App\Http\Requests\StoreToleranciaRequest;
use App\Http\Requests\UpdateToleranciaRequest;
use App\Http\Resources\ToleranciaResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class ToleranciaController extends Controller
{
    /**
     * Display a listing of tolerancias.
     * GET /api/tolerancias
     */
    public function index(Request $request)
    {
        try {
            $authUser = Auth::user();
            $query = Tolerancia::query();
            if ($authUser->idrol != 1) {
                // Soportar ambos posibles nombres de campo en el usuario
                $empresaId = $authUser->id_empresa ?? $authUser->id_company;
                $query->where('id_empresa', $empresaId);
            }

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
     * Buscar tolerancias por id_empresa
     * GET /api/tolerancias/by-empresa?id_empresa=valor&per_page=15
     */
    public function byEmpresa(Request $request)
    {
        try {
            $idEmpresa = $request->input('id_empresa');
            if (empty($idEmpresa)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar el parámetro id_empresa'
                ], 400);
            }
            $query = Tolerancia::where('id_empresa', $idEmpresa);
            $perPage = $request->input('per_page', 15);
            $tolerancias = $query->orderBy('minutos')->paginate($perPage);
            return response()->json([
                'success' => true,
                'data' => ToleranciaResource::collection($tolerancias->items()),
                'pagination' => [
                    'current_page' => $tolerancias->currentPage(),
                    'last_page' => $tolerancias->lastPage(),
                    'per_page' => $tolerancias->perPage(),
                    'total' => $tolerancias->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda por empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created tolerancia.
     * POST /api/tolerancias
     */
    public function store(StoreToleranciaRequest $request)
    {
        try {
            $data = $request->validated();
            $authUser = Auth::user();
            // Asignar id_empresa correctamente para superusuario y usuarios normales
            if ($authUser->idrol == 1) {
                // Superusuario: permite id_empresa del request, o del usuario si no viene
                $data['id_empresa'] = $data['id_empresa'] ?? $authUser->id_empresa ?? $authUser->id_company;
            } else {
                // Usuario normal: siempre forzar su empresa
                $data['id_empresa'] = $authUser->id_empresa ?? $authUser->id_company;
            }
            $tolerancia = Tolerancia::create($data);

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
            $data = $request->validated();
            $authUser = Auth::user();
            // Asignar id_empresa correctamente para superusuario y usuarios normales
            if ($authUser->idrol == 1) {
                $data['id_empresa'] = $data['id_empresa'] ?? $authUser->id_empresa ?? $authUser->id_company;
            } else {
                $data['id_empresa'] = $authUser->id_empresa ?? $authUser->id_company;
            }
            $tolerancia->update($data);

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

    public function search(Request $request)
    {
        try {
            $authUser = Auth::user();
            $query = Tolerancia::query();
            if ($authUser->idrol != 1) {
                $empresaId = $authUser->id_empresa ?? $authUser->id_company;
                $query->where('id_empresa', $empresaId);
            }
            $q = $request->input('q');
            if (!empty($q)) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('descripcion', 'like', "%$q%")
                        ->orWhere('minutos', 'like', "%$q%");
                });
            }
            $perPage = $request->input('per_page', 15);
            $tolerancias = $query->orderBy('minutos')->paginate($perPage);
            return response()->json([
                'success' => true,
                'data' => ToleranciaResource::collection($tolerancias->items()),
                'pagination' => [
                    'current_page' => $tolerancias->currentPage(),
                    'last_page' => $tolerancias->lastPage(),
                    'per_page' => $tolerancias->perPage(),
                    'total' => $tolerancias->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda de tolerancias',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

