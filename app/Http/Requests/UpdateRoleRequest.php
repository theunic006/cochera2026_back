<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
            'descripcion' => [
                'required',
                'string',
                'max:50',
                Rule::unique('roles', 'descripcion')->ignore($this->role ?? $this->id)
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'descripcion.required' => 'La descripción del role es requerida',
            'descripcion.string' => 'La descripción debe ser un texto válido',
            'descripcion.max' => 'La descripción no puede tener más de 50 caracteres',
            'descripcion.unique' => 'Ya existe un role con esta descripción'
        ];
    }
}
