<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegistroRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_vehiculo' => 'sometimes|integer|exists:vehiculos,id',
            'id_user' => 'sometimes|integer|exists:users,id',
            'id_empresa' => 'sometimes|integer|exists:companies,id',
            'fecha' => 'nullable|date',
        ];
    }
}
