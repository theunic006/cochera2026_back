<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Permitir la autorización (se maneja en middleware)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'string'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['nullable', 'in:' . implode(',', Company::getEstadosDisponibles())],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la company es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'ubicacion.string' => 'La ubicación debe ser una cadena de texto.',
            'ubicacion.max' => 'La ubicación no puede tener más de 255 caracteres.',
            'logo.string' => 'El logo debe ser una cadena de texto.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'estado.in' => 'El estado debe ser uno de los valores permitidos: ' . implode(', ', Company::getEstadosDisponibles()) . '.',
        ];
    }
}
