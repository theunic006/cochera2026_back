<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistroRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_vehiculo' => 'required|integer|exists:vehiculos,id',
            'id_user' => 'required|integer|exists:users,id',
            'id_empresa' => 'required|integer|exists:companies,id',
            'fecha' => 'nullable|date',
        ];
    }
}

