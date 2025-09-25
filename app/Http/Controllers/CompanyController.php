<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $companies = Company::orderBy('created_at', 'desc')
                             ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'message' => 'Companies obtenidas exitosamente',
                'data' => CompanyResource::collection($companies->items()),
                'pagination' => [
                    'current_page' => $companies->currentPage(),
                    'total_pages' => $companies->lastPage(),
                    'per_page' => $companies->perPage(),
                    'total' => $companies->total(),
                    'from' => $companies->firstItem(),
                    'to' => $companies->lastItem(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las companies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        try {
            $company = Company::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Company creada exitosamente',
                'data' => new CompanyResource($company)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la company',
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
            $company = Company::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Company encontrada',
                'data' => new CompanyResource($company)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company no encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, string $id): JsonResponse
    {
        try {
            $company = Company::findOrFail($id);
            $company->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Company actualizada exitosamente',
                'data' => new CompanyResource($company->fresh())
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company no encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la company',
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
            $company = Company::findOrFail($id);
            $company->delete();

            return response()->json([
                'success' => true,
                'message' => 'Company eliminada exitosamente'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company no encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search companies by name, ubicacion or descripcion.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('query', '');
            $perPage = $request->get('per_page', 10);

            if (empty($query)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetro de búsqueda requerido'
                ], 400);
            }

            $companies = Company::where('nombre', 'LIKE', "%{$query}%")
                             ->orWhere('ubicacion', 'LIKE', "%{$query}%")
                             ->orWhere('descripcion', 'LIKE', "%{$query}%")
                             ->orderBy('created_at', 'desc')
                             ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => "Resultados de búsqueda para: {$query}",
                'data' => CompanyResource::collection($companies->items()),
                'pagination' => [
                    'current_page' => $companies->currentPage(),
                    'total_pages' => $companies->lastPage(),
                    'per_page' => $companies->perPage(),
                    'total' => $companies->total(),
                    'from' => $companies->firstItem(),
                    'to' => $companies->lastItem(),
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
