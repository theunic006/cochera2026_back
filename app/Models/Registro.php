<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Registro extends Model
{
    protected $table = 'registros';
    protected $fillable = [
        'id_vehiculo',
        'id_user',
        'id_empresa',
        'fecha',
    ];
    // Relación con vehículo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }

     public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_empresa');
    }


}
