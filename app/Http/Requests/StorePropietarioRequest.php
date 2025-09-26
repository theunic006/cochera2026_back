<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropietarioRequest extends FormRequest
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
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'documento' => 'required|string|max:50|unique:propietarios,documento',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombres.required' => 'Los nombres del propietario son obligatorios.',
            'nombres.string' => 'Los nombres deben ser una cadena de texto.',
            'nombres.max' => 'Los nombres no pueden tener más de 100 caracteres.',
            'apellidos.required' => 'Los apellidos del propietario son obligatorios.',
            'apellidos.string' => 'Los apellidos deben ser una cadena de texto.',
            'apellidos.max' => 'Los apellidos no pueden tener más de 100 caracteres.',
            'documento.required' => 'El documento es obligatorio.',
            'documento.string' => 'El documento debe ser una cadena de texto.',
            'documento.max' => 'El documento no puede tener más de 50 caracteres.',
            'documento.unique' => 'Ya existe un propietario con este documento.',
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'email.email' => 'Debe proporcionar un email válido.',
            'email.max' => 'El email no puede tener más de 100 caracteres.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',
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
