<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'nombre' => ['sometimes', 'required', 'string', 'max:100'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'capacidad' => ['nullable', 'integer', 'min:0'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['nullable', 'in:' . implode(',', Company::getEstadosDisponibles())],
            'ngrok' => ['nullable', 'string', 'max:255'],
            'ruc' => ['nullable', 'string', 'max:20'],
            'token' => ['nullable', 'string', 'max:255'],
            'imp_input' => ['nullable', 'string', 'max:255'],
            'imp_output' => ['nullable', 'string', 'max:255'],
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
            'capacidad.integer' => 'La capacidad debe ser un número entero.',
            'capacidad.min' => 'La capacidad no puede ser negativa.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'estado.in' => 'El estado debe ser uno de los valores permitidos: ' . implode(', ', Company::getEstadosDisponibles()) . '.',
            'imp_input.string' => 'La impresora de entrada debe ser una cadena de texto.',
            'imp_input.max' => 'El nombre de la impresora de entrada no puede tener más de 255 caracteres.',
            'imp_output.string' => 'La impresora de salida debe ser una cadena de texto.',
            'imp_output.max' => 'El nombre de la impresora de salida no puede tener más de 255 caracteres.',
        ];
    }
}
