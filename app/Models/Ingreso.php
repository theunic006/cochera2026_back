<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $table = 'ingresos';
    protected $fillable = [
        'fecha_ingreso',
        'hora_ingreso',
        'id_user',
        'id_empresa',
        'id_vehiculo',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }

}
