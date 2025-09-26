<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropietarioRequest extends FormRequest
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
        return [
            'nombre' => 'sometimes|required|string|max:100',
            'telefono' => 'sometimes|nullable|string|max:20',
            'tipo_boleta' => 'sometimes|nullable|string|max:50',
            'numero_boleta' => 'sometimes|nullable|string|max:50',
            'id_registro' => 'sometimes|nullable|integer',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del propietario es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'tipo_boleta.string' => 'El tipo de boleta debe ser una cadena de texto.',
            'tipo_boleta.max' => 'El tipo de boleta no puede tener más de 50 caracteres.',
            'numero_boleta.string' => 'El número de boleta debe ser una cadena de texto.',
            'numero_boleta.max' => 'El número de boleta no puede tener más de 50 caracteres.',
            'id_registro.integer' => 'El ID de registro debe ser un número entero.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del propietario',
            'telefono' => 'teléfono',
            'tipo_boleta' => 'tipo de boleta',
            'numero_boleta' => 'número de boleta',
            'id_registro' => 'ID de registro',
        ];
    }
}
