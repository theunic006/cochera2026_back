<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehiculoRequest extends FormRequest
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
            'placa' => [
                'required',
                'string',
                'max:10',
                'regex:/^[A-Z0-9\-]+$/'
            ],
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'color' => 'required|string|max:30',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculos,id'
        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    public function messages(): array
    {
        return [
            'placa.required' => 'La placa es obligatoria',
            'placa.unique' => 'Ya existe un vehículo con esta placa',
            'placa.regex' => 'La placa solo puede contener letras mayúsculas, números y guiones',
            'marca.required' => 'La marca es obligatoria',
            'modelo.required' => 'El modelo es obligatorio',
            'color.required' => 'El color es obligatorio',
            'anio.required' => 'El año es obligatorio',
            'anio.integer' => 'El año debe ser un número entero',
            'anio.min' => 'El año no puede ser menor a 1900',
            'anio.max' => 'El año no puede ser mayor al próximo año',
            'tipo_vehiculo_id.required' => 'El tipo de vehículo es obligatorio',
            'tipo_vehiculo_id.exists' => 'El tipo de vehículo seleccionado no existe'
        ];
    }
}
