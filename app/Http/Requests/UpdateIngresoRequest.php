<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIngresoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_ingreso' => ['sometimes', 'date'],
            'hora_ingreso' => ['sometimes', 'date_format:H:i:s'],
            'id_registro' => ['sometimes', 'integer', 'exists:registros,id'],
            'id_user' => ['sometimes', 'integer', 'exists:users,id'],
            'id_empresa' => ['sometimes', 'integer', 'exists:empresas,id'],
        ];
    }
}
