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

    // Relación: Un ingreso tiene muchas observaciones a través del vehículo
    public function observaciones()
    {
        return $this->hasMany(\App\Models\Observacion::class, 'id_vehiculo', 'id_vehiculo');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

        public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'tipo_vehiculo_id');
    }


}
