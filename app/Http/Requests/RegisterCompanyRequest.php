<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class RegisterCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Permitir registro público sin autenticación
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100', 'unique:companies,nombre'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'], // 2MB máximo
            'capacidad' => ['nullable', 'integer', 'min:1'], // Al menos 1 si se especifica
            'descripcion' => ['nullable', 'string', 'max:1000'], // Límite razonable
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la empresa es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'nombre.unique' => 'Ya existe una empresa con este nombre.',

            'ubicacion.string' => 'La ubicación debe ser una cadena de texto.',
            'ubicacion.max' => 'La ubicación no puede tener más de 255 caracteres.',

            'logo.file' => 'El logo debe ser un archivo válido.',
            'logo.mimes' => 'El logo debe ser una imagen en formato JPG, JPEG, PNG o GIF.',
            'logo.max' => 'El logo no puede ser mayor a 2MB.',

            'capacidad.integer' => 'La capacidad debe ser un número entero.',
            'capacidad.min' => 'La capacidad debe ser al menos 1.',

            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre de la empresa',
            'ubicacion' => 'ubicación',
            'logo' => 'logo',
            'capacidad' => 'capacidad',
            'descripcion' => 'descripción',
        ];
    }
}
