<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehiculoPropietarioRequest extends FormRequest
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
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'propietario_id' => 'required|exists:propietarios,id',
            'fecha_inicio' => 'required|date|before_or_equal:today',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    public function messages(): array
    {
        return [
            'vehiculo_id.required' => 'El vehículo es obligatorio',
            'vehiculo_id.exists' => 'El vehículo seleccionado no existe',
            'propietario_id.required' => 'El propietario es obligatorio',
            'propietario_id.exists' => 'El propietario seleccionado no existe',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida',
            'fecha_inicio.before_or_equal' => 'La fecha de inicio no puede ser futura',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
        ];
    }
}
