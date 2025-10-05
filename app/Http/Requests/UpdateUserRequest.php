<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true; // Permitir la actualización de usuarios
    }

    public function rules(): array
    {
    $userId = $this->route('id'); // El parámetro de la ruta es 'id' según UserController@update
    return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'password' => 'sometimes|required|string|min:8|confirmed',
            'idrol' => 'sometimes|nullable|exists:roles,id',
            'id_company' => 'sometimes|nullable|exists:companies,id',
            'estado' => 'sometimes|nullable|in:ACTIVO,INACTIVO,SUSPENDIDO',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'idrol.exists' => 'El rol seleccionado no existe.',
            'id_company.exists' => 'La empresa seleccionada no existe.',
        ];
    }
}
