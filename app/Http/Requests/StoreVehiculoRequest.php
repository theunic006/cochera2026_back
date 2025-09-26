<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehiculoRequest extends FormRequest
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
            'placa' => 'required|string|max:15|unique:vehiculos,placa',
            'modelo' => 'nullable|string|max:50',
            'marca' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'id_tipo_vehiculo' => 'nullable|integer|exists:tipo_vehiculos,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'placa.required' => 'La placa del vehículo es obligatoria.',
            'placa.string' => 'La placa debe ser una cadena de texto.',
            'placa.max' => 'La placa no puede tener más de 15 caracteres.',
            'placa.unique' => 'Ya existe un vehículo con esta placa.',
            'modelo.string' => 'El modelo debe ser una cadena de texto.',
            'modelo.max' => 'El modelo no puede tener más de 50 caracteres.',
            'marca.string' => 'La marca debe ser una cadena de texto.',
            'marca.max' => 'La marca no puede tener más de 50 caracteres.',
            'color.string' => 'El color debe ser una cadena de texto.',
            'color.max' => 'El color no puede tener más de 30 caracteres.',
            'id_tipo_vehiculo.integer' => 'El ID del tipo de vehículo debe ser un número entero.',
            'id_tipo_vehiculo.exists' => 'El tipo de vehículo seleccionado no existe.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'placa' => 'placa del vehículo',
            'modelo' => 'modelo',
            'marca' => 'marca',
            'color' => 'color',
            'id_tipo_vehiculo' => 'tipo de vehículo',
        ];
    }
}
