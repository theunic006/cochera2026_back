<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Services\RecaptchaService;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
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
            $companies = Company::withCount('users')
                             ->orderBy('created_at', 'desc')
                             ->paginate($perPage);

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
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->all();

            // Manejo de imagen/logo
            $companiesPath = storage_path('app/public/companies');
            if (!file_exists($companiesPath)) {
                mkdir($companiesPath, 0775, true);
                Log::info("Carpeta companies creada: " . $companiesPath);
            }

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                Log::info("Archivo recibido: " . $file->getClientOriginalName() . " Tamaño: " . $file->getSize() . " bytes");

                if ($file->isValid()) {
                    try {
                        // Verificar permisos antes de guardar
                        if (!is_writable($companiesPath)) {
                            Log::error("La carpeta no es escribible: " . $companiesPath);
                            throw new \Exception("La carpeta de destino no es escribible");
                        }

                        $path = $file->store('companies', 'public');
                        $fullPath = storage_path('app/public/' . $path);

                        if (file_exists($fullPath)) {
                            $data['logo'] = $path;
                            Log::info("Archivo guardado exitosamente: " . $fullPath);
                        } else {
                            Log::error("El archivo no se guardó correctamente: " . $fullPath);
                            throw new \Exception("Error al guardar el archivo");
                        }
                    } catch (\Exception $e) {
                        Log::error("Error al procesar logo: " . $e->getMessage());
                        throw new \Exception("Error al subir el logo: " . $e->getMessage());
                    }
                } else {
                    Log::error("Archivo inválido: " . $file->getErrorMessage());
                    throw new \Exception("El archivo subido es inválido");
                }
            }


            $company = Company::create($data);

            // Crear registro en tipo_vehiculos con nombre 'nuevo', valor '3' y el id_empresa de la empresa recién creada
            $tipoVehiculoCreado = \App\Models\TipoVehiculo::create([
                'nombre' => 'Nuevo',
                'valor' => 3,
                'id_empresa' => $company->id
            ]);

            $password = '12345678';
            $adminUser = User::create([
                'name' => 'Admin ' . $company->nombre,
                'email' => 'admin@' . Str::slug($company->nombre) . '.com',
                'password' => Hash::make($password),
                'categoria' => 'Administrador',
                'idrol' => 2,
                'id_company' => $company->id,
                'estado' => 'ACTIVO',
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
                        'password' => $password,
                        'categoria' => $adminUser->categoria,
                        'role' => 'Administrador General'
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la Empresa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Public registration endpoint for companies without authentication
     */
    public function publicRegister(Request $request): JsonResponse
    {
        try {
            // Validar datos de entrada
            $request->validate([
                'nombre' => 'required|string|max:255|unique:companies,nombre',
                'ubicacion' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'capacidad' => 'required|integer|min:1',
                'estado' => 'nullable|in:activo,inactivo,suspendido,pendiente',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            DB::beginTransaction();

            $data = $request->all();
            $data['estado'] = $data['estado'] ?? 'pendiente'; // Por defecto pendiente para revisión

            // Manejo de imagen/logo
            $companiesPath = storage_path('app/public/companies');
            if (!file_exists($companiesPath)) {
                mkdir($companiesPath, 0775, true);
                Log::info("Carpeta companies creada: " . $companiesPath);
            }

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                Log::info("Archivo recibido en registro público: " . $file->getClientOriginalName() . " Tamaño: " . $file->getSize() . " bytes");

                if ($file->isValid()) {
                    try {
                        // Verificar permisos antes de guardar
                        if (!is_writable($companiesPath)) {
                            Log::error("La carpeta no es escribible: " . $companiesPath);
                            throw new \Exception("La carpeta de destino no es escribible");
                        }

                        $path = $file->store('companies', 'public');
                        $fullPath = storage_path('app/public/' . $path);

                        if (file_exists($fullPath)) {
                            $data['logo'] = $path;
                            Log::info("Archivo guardado exitosamente en registro público: " . $fullPath);
                        } else {
                            Log::error("El archivo no se guardó correctamente: " . $fullPath);
                            throw new \Exception("Error al guardar el archivo");
                        }
                    } catch (\Exception $e) {
                        Log::error("Error al procesar logo en registro público: " . $e->getMessage());
                        throw new \Exception("Error al subir el logo: " . $e->getMessage());
                    }
                } else {
                    Log::error("Archivo inválido en registro público: " . $file->getErrorMessage());
                    throw new \Exception("El archivo subido es inválido");
                }
            }

            $company = Company::create($data);

            // Crear registro en tipo_vehiculos con nombre 'Nuevo', valor '3' y el id_empresa de la empresa recién creada
            $tipoVehiculoCreado = \App\Models\TipoVehiculo::create([
                'nombre' => 'Nuevo',
                'valor' => 3,
                'id_empresa' => $company->id
            ]);

            $password = '12345678';
            $adminUser = User::create([
                'name' => 'Admin ' . $company->nombre,
                'email' => 'admin@' . Str::slug($company->nombre) . '.com',
                'password' => Hash::make($password),
                'categoria' => 'Administrador',
                'idrol' => 2,
                'id_company' => $company->id,
                'estado' => 'ACTIVO',
                'email_verified_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Empresa registrada exitosamente. Su estado es "pendiente" y será revisada por un administrador.',
                'data' => [
                    'company' => new CompanyResource($company),
                    'admin_user' => [
                        'id' => $adminUser->id,
                        'name' => $adminUser->name,
                        'email' => $adminUser->email,
                        'password' => $password,
                        'categoria' => $adminUser->categoria,
                        'role' => 'Administrador General',
                        'instructions' => 'Guarde estas credenciales. Podrá acceder cuando la empresa sea aprobada.'
                    ]
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la empresa',
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
    //public function update(Request $request, string $id): JsonResponse
    public function update(UpdateCompanyRequest $request, string $id): JsonResponse
    {
        try {
            $company = Company::findOrFail($id);
            $data = $request->validated();

            // Manejo de imagen/logo
            $companiesPath = storage_path('app/public/companies');
            if (!file_exists($companiesPath)) {
                mkdir($companiesPath, 0775, true);
                Log::info("Carpeta companies creada: " . $companiesPath);
            }

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                Log::info("Archivo recibido para actualización: " . $file->getClientOriginalName() . " Tamaño: " . $file->getSize() . " bytes");

                if ($file->isValid()) {
                    try {
                        // Verificar permisos antes de guardar
                        if (!is_writable($companiesPath)) {
                            Log::error("La carpeta no es escribible: " . $companiesPath);
                            throw new \Exception("La carpeta de destino no es escribible");
                        }

                        // Eliminar imagen anterior si existe
                        if ($company->logo && file_exists(storage_path('app/public/' . $company->logo))) {
                            unlink(storage_path('app/public/' . $company->logo));
                            Log::info("Imagen anterior eliminada: " . $company->logo);
                        }

                        $path = $file->store('companies', 'public');
                        $fullPath = storage_path('app/public/' . $path);

                        if (file_exists($fullPath)) {
                            $data['logo'] = $path;
                            Log::info("Archivo actualizado exitosamente: " . $fullPath);
                        } else {
                            Log::error("El archivo no se guardó correctamente: " . $fullPath);
                            throw new \Exception("Error al guardar el archivo");
                        }
                    } catch (\Exception $e) {
                        Log::error("Error al procesar logo en actualización: " . $e->getMessage());
                        throw new \Exception("Error al actualizar el logo: " . $e->getMessage());
                    }
                } else {
                    Log::error("Archivo inválido en actualización: " . $file->getErrorMessage());
                    throw new \Exception("El archivo subido es inválido");
                }
            }

            $company->update($data);

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
            // Verificar si existen ingresos asociados a esta empresa
            $tieneIngresos = \App\Models\Ingreso::where('id_empresa', $company->id)->exists();
            if ($tieneIngresos) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar: existen ingresos asociados a esta empresa.'
                ], 409);
            }
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
