<?php

namespace App\Http\Controllers;

use App\Models\TipoVehiculo;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTipoVehiculoRequest;
use App\Http\Requests\UpdateTipoVehiculoRequest;
use App\Http\Resources\TipoVehiculoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class TipoVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $allowed = [10, 15, 20, 30, 50, 100];
            if (!in_array((int)$perPage, $allowed)) {
                $perPage = 15;
            }
            $query = TipoVehiculo::orderBy('nombre', 'asc');
            if (Auth::user()->idrol != 1) {
                $empresaId = Auth::user()->id_empresa ?? Auth::user()->id_company;
                $query->where('id_empresa', $empresaId);
            }
            $tiposVehiculo = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Tipos de vehículo obtenidos exitosamente',
                'data' => TipoVehiculoResource::collection($tiposVehiculo->items()),
                'pagination' => [
                    'current_page' => $tiposVehiculo->currentPage(),
                    'total_pages' => $tiposVehiculo->lastPage(),
                    'per_page' => $tiposVehiculo->perPage(),
                    'total' => $tiposVehiculo->total(),
                    'from' => $tiposVehiculo->firstItem(),
                    'to' => $tiposVehiculo->lastItem(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los tipos de vehículo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoVehiculoRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $empresaId = Auth::user()->id_empresa ?? Auth::user()->id_company;
            $data['id_empresa'] = $empresaId;
            $tipoVehiculo = TipoVehiculo::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Tipo de vehículo creado exitosamente',
                'data' => new TipoVehiculoResource($tipoVehiculo)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el tipo de vehículo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $tipoVehiculo = TipoVehiculo::findOrFail($id);
            $empresaId = Auth::user()->id_empresa ?? Auth::user()->id_company;
            if (Auth::user()->idrol != 1 && $tipoVehiculo->id_empresa != $empresaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado para ver este tipo de vehículo'
                ], 403);
            }
            return response()->json([
                'success' => true,
                'message' => 'Tipo de vehículo encontrado',
                'data' => new TipoVehiculoResource($tipoVehiculo)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de vehículo no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el tipo de vehículo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoVehiculoRequest $request, string $id): JsonResponse
    {
        try {
            $tipoVehiculo = TipoVehiculo::findOrFail($id);
            $empresaId = Auth::user()->id_empresa ?? Auth::user()->id_company;
            if (Auth::user()->idrol != 1 && $tipoVehiculo->id_empresa != $empresaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado para actualizar este tipo de vehículo'
                ], 403);
            }
            $data = $request->validated();
            $data['id_empresa'] = $empresaId;
            $tipoVehiculo->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Tipo de vehículo actualizado exitosamente',
                'data' => new TipoVehiculoResource($tipoVehiculo->fresh())
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de vehículo no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el tipo de vehículo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $tipoVehiculo = TipoVehiculo::findOrFail($id);
            $empresaId = Auth::user()->id_empresa ?? Auth::user()->id_company;
            if (Auth::user()->idrol != 1 && $tipoVehiculo->id_empresa != $empresaId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado para eliminar este tipo de vehículo'
                ], 403);
            }
            $nombre = $tipoVehiculo->nombre;
            $tipoVehiculo->delete();

            return response()->json([
                'success' => true,
                'message' => "Tipo de vehículo '{$nombre}' eliminado exitosamente",
                'data' => null
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de vehículo no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el tipo de vehículo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search tipos de vehiculo by nombre.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $queryStr = $request->get('query', '');
            $perPage = $request->get('per_page', 10);

            if (empty($queryStr)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetro de búsqueda requerido'
                ], 400);
            }

            $query = TipoVehiculo::byNombre($queryStr)->orderBy('nombre', 'asc');
            if (Auth::user()->idrol != 1) {
                $empresaId = Auth::user()->id_empresa ?? Auth::user()->id_company;
                $query->where('id_empresa', $empresaId);
            }
            $tiposVehiculo = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => "Resultados de búsqueda para: {$queryStr}",
                'data' => TipoVehiculoResource::collection($tiposVehiculo->items()),
                'pagination' => [
                    'current_page' => $tiposVehiculo->currentPage(),
                    'total_pages' => $tiposVehiculo->lastPage(),
                    'per_page' => $tiposVehiculo->perPage(),
                    'total' => $tiposVehiculo->total(),
                    'from' => $tiposVehiculo->firstItem(),
                    'to' => $tiposVehiculo->lastItem(),
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

    /**
     * Get tipos de vehiculo con valor definido.
     */
    public function conValor(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);

            $query = TipoVehiculo::conValor()->orderBy('valor', 'asc');
            if (Auth::user()->idrol != 1) {
                $empresaId = Auth::user()->id_empresa ?? Auth::user()->id_company;
                $query->where('id_empresa', $empresaId);
            }
            $tiposVehiculo = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Tipos de vehículo con valor obtenidos exitosamente',
                'data' => TipoVehiculoResource::collection($tiposVehiculo->items()),
                'pagination' => [
                    'current_page' => $tiposVehiculo->currentPage(),
                    'total_pages' => $tiposVehiculo->lastPage(),
                    'per_page' => $tiposVehiculo->perPage(),
                    'total' => $tiposVehiculo->total(),
                    'from' => $tiposVehiculo->firstItem(),
                    'to' => $tiposVehiculo->lastItem(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipos de vehículo con valor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tipos de vehiculo por rango de valor.
     */
    public function porRangoValor(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'min' => 'nullable|numeric|min:0',
                'max' => 'nullable|numeric|min:0',
                'per_page' => 'integer|min:1|max:100'
            ]);

            $min = $request->get('min');
            $max = $request->get('max');
            $perPage = $request->get('per_page', 10);

            if ($min !== null && $max !== null && $min > $max) {
                return response()->json([
                    'success' => false,
                    'message' => 'El valor mínimo no puede ser mayor al valor máximo'
                ], 400);
            }

            $query = TipoVehiculo::byRangoValor($min, $max)->orderBy('valor', 'asc');
            if (Auth::user()->idrol != 1) {
                $empresaId = Auth::user()->id_empresa ?? Auth::user()->id_company;
                $query->where('id_empresa', $empresaId);
            }
            $tiposVehiculo = $query->paginate($perPage);

            $rangoMensaje = '';
            if ($min !== null && $max !== null) {
                $rangoMensaje = "entre {$min} y {$max}";
            } elseif ($min !== null) {
                $rangoMensaje = "mayor o igual a {$min}";
            } elseif ($max !== null) {
                $rangoMensaje = "menor o igual a {$max}";
            }

            return response()->json([
                'success' => true,
                'message' => "Tipos de vehículo con valor {$rangoMensaje} obtenidos exitosamente",
                'data' => TipoVehiculoResource::collection($tiposVehiculo->items()),
                'pagination' => [
                    'current_page' => $tiposVehiculo->currentPage(),
                    'total_pages' => $tiposVehiculo->lastPage(),
                    'per_page' => $tiposVehiculo->perPage(),
                    'total' => $tiposVehiculo->total(),
                    'from' => $tiposVehiculo->firstItem(),
                    'to' => $tiposVehiculo->lastItem(),
                ],
                'filtros' => [
                    'min' => $min,
                    'max' => $max
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipos de vehículo por rango de valor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
