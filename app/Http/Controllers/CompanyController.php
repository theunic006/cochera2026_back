<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

            $companies = Company::withCount('users')
                             ->orderBy('created_at', 'desc')
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
            // Usar transacción para asegurar que ambas operaciones se completen
            DB::beginTransaction();

            // Crear la company
            $company = Company::create($request->validated());

            // Password fijo para el usuario administrador
            $password = 'admin123456';

            // Crear usuario administrador automáticamente
            $adminUser = User::create([
                'name' => 'Admin ' . $company->nombre,
                'email' => 'admin@' . Str::slug($company->nombre) . '.com',
                'password' => Hash::make($password),
                'categoria' => 'Administrador',
                'idrol' => 2, // Administrador General
                'id_company' => $company->id,
                'email_verified_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Company creada exitosamente con usuario administrador',
                'data' => [
                    'company' => new CompanyResource($company),
                    'admin_user' => [
                        'id' => $adminUser->id,
                        'name' => $adminUser->name,
                        'email' => $adminUser->email,
                        'password' => $password, // Solo mostrar en creación
                        'categoria' => $adminUser->categoria,
                        'role' => 'Administrador General'
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la company y usuario administrador',
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
            $company = Company::withCount('users')->findOrFail($id);

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

            $companies = Company::withCount('users')
                             ->where('nombre', 'LIKE', "%{$query}%")
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

    /**
     * Generate a secure password for admin user.
     */
    private function generateSecurePassword(): string
    {
        // Generar password de 12 caracteres con mayúsculas, minúsculas, números y símbolos
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%&*';

        $password = '';

        // Asegurar al menos un carácter de cada tipo
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $symbols[rand(0, strlen($symbols) - 1)];

        // Completar hasta 12 caracteres
        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = 4; $i < 12; $i++) {
            $password .= $allChars[rand(0, strlen($allChars) - 1)];
        }

        // Mezclar los caracteres
        return str_shuffle($password);
    }

    /**
     * Activar una company.
     */
    public function activate(string $id): JsonResponse
    {
        try {
            $company = Company::findOrFail($id);

            if ($company->activate()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Company activada exitosamente',
                    'data' => new CompanyResource($company->fresh())
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo activar la company'
            ], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company no encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al activar la company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suspender una company.
     */
    public function suspend(string $id): JsonResponse
    {
        try {
            $company = Company::findOrFail($id);

            if ($company->suspend()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Company suspendida exitosamente',
                    'data' => new CompanyResource($company->fresh())
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo suspender la company'
            ], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company no encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al suspender la company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar el estado de una company.
     */
    public function changeStatus(Request $request, string $id): JsonResponse
    {
        try {
            $request->validate([
                'estado' => 'required|in:' . implode(',', Company::getEstadosDisponibles())
            ]);

            $company = Company::findOrFail($id);

            if ($company->changeEstado($request->estado)) {
                return response()->json([
                    'success' => true,
                    'message' => "Estado cambiado a '{$request->estado}' exitosamente",
                    'data' => new CompanyResource($company->fresh())
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo cambiar el estado de la company'
            ], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company no encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado de la company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener companies por estado.
     */
    public function getByStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'estado' => 'required|in:' . implode(',', Company::getEstadosDisponibles()),
                'per_page' => 'integer|min:1|max:100'
            ]);

            $perPage = $request->get('per_page', 10);
            $companies = Company::withCount('users')
                                ->byEstado($request->estado)
                                ->latest()
                                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => "Companies con estado '{$request->estado}' obtenidas exitosamente",
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
                'message' => 'Error al obtener companies por estado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener los estados disponibles.
     */
    public function getAvailableStatuses(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Estados disponibles obtenidos exitosamente',
                'data' => [
                    'estados' => Company::getEstadosDisponibles(),
                    'constantes' => [
                        'ACTIVO' => Company::ESTADO_ACTIVO,
                        'SUSPENDIDO' => Company::ESTADO_SUSPENDIDO,
                        'INACTIVO' => Company::ESTADO_INACTIVO,
                        'PENDIENTE' => Company::ESTADO_PENDIENTE,
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estados disponibles',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
