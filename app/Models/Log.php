<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';
    protected $fillable = [
        'accion',
        'id_user',
        'fecha',
        'id_empresa',
        'ip',
        'detalle',
        'estado',
        'user_agent',
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relación con empresa
    public function empresa()
    {
        return $this->belongsTo(Company::class, 'id_empresa');
    }
}
