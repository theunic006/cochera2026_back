<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    protected $table = 'salidas';
    protected $fillable = [
        'placa',
        'user',
        'fecha_salida',
        'hora_salida',
        'tiempo',
        'precio',
        'tipo_pago',
        'id_user',
        'id_empresa',
        'id_registro',
    ];

    public function registro()
    {
        return $this->belongsTo(Registro::class, 'id_registro');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function empresa()
    {
        return $this->belongsTo(Company::class, 'id_empresa');
    }
}
