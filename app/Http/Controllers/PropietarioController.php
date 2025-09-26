<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropietarioRequest;
use App\Http\Requests\UpdatePropietarioRequest;
use App\Http\Resources\PropietarioResource;
use App\Models\Propietario;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PropietarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Propietario::query();

        // Filtros
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('documento', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('documento')) {
            $query->where('documento', 'like', '%' . $request->get('documento') . '%');
        }

        $propietarios = $query->orderBy('apellidos')->orderBy('nombres')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => PropietarioResource::collection($propietarios->items()),
            'pagination' => [
                'current_page' => $propietarios->currentPage(),
                'per_page' => $propietarios->perPage(),
                'total' => $propietarios->total(),
                'last_page' => $propietarios->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePropietarioRequest $request): JsonResponse
    {
        $propietario = Propietario::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Propietario creado exitosamente',
            'data' => new PropietarioResource($propietario)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Propietario $propietario): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new PropietarioResource($propietario)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropietarioRequest $request, Propietario $propietario): JsonResponse
    {
        $propietario->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Propietario actualizado exitosamente',
            'data' => new PropietarioResource($propietario)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Propietario $propietario): JsonResponse
    {
        $propietario->delete();

        return response()->json([
            'success' => true,
            'message' => 'Propietario eliminado exitosamente'
        ]);
    }
}
