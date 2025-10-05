<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreTipoVehiculoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = Auth::user();
        $empresaId = $user->id_empresa ?? $user->id_company;

        $rules = [
            'valor' => 'nullable|numeric|min:0',
            'nombre' => [
                'required',
                'string',
                'max:100',
                // Si es superusuario, no validar unicidad por empresa
                $user && $user->idrol == 1
                    ? Rule::unique('tipo_vehiculos', 'nombre')
                    : Rule::unique('tipo_vehiculos', 'nombre')->where('id_empresa', $empresaId),
            ],
            ];

            return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del tipo de vehículo es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 50 caracteres.',
            'nombre.unique' => 'Ya existe un tipo de vehículo con este nombre.',
            'valor.numeric' => 'El valor debe ser un número.',
            'valor.min' => 'El valor no puede ser negativo.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del tipo de vehículo',
            'valor' => 'valor',
        ];
    }
}
