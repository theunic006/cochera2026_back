<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateToleranciaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Permite la autorización cuando el usuario está autenticado
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'minutos' => [
                'required',
                'integer',
                'min:1',
                'max:1440'
            ],
            'descripcion' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tolerancia', 'descripcion')->ignore($this->tolerancia->id ?? null)
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'minutos.required' => 'Los minutos son requeridos',
            'minutos.integer' => 'Los minutos deben ser un número entero',
            'minutos.min' => 'Los minutos deben ser al menos 1',
            'minutos.max' => 'Los minutos no pueden ser más de 1440',
            'descripcion.required' => 'La descripción es requerida',
            'descripcion.string' => 'La descripción debe ser texto',
            'descripcion.max' => 'La descripción no puede exceder 100 caracteres',
            'descripcion.unique' => 'Ya existe una tolerancia con esta descripción'
        ];
    }
}
