<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreToleranciaRequest extends FormRequest
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
            'minutos' => 'required|integer|min:1|max:1440',
            'descripcion' => 'required|string|max:100|unique:tolerancia,descripcion',
        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    public function messages(): array
    {
        return [
            'minutos.required' => 'Los minutos de tolerancia son obligatorios',
            'minutos.integer' => 'Los minutos deben ser un número entero',
            'minutos.min' => 'Los minutos deben ser al menos 1',
            'minutos.max' => 'Los minutos no pueden exceder 1440 (24 horas)',
            'descripcion.required' => 'La descripción es obligatoria',
            'descripcion.string' => 'La descripción debe ser texto',
            'descripcion.max' => 'La descripción no puede exceder 100 caracteres',
            'descripcion.unique' => 'Ya existe una tolerancia con esta descripción',
        ];
    }
}
