<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Propietario extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'propietarios';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nombre',
        'telefono',
        'tipo_boleta',
        'numero_boleta',
        'id_registro',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'id_registro' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Laravel timestamps enabled (created_at, updated_at)
     */
    public $timestamps = true;

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * Scope: Filtrar por nombre
     */
    public function scopeByNombre($query, $nombre)
    {
        return $query->where('nombre', 'LIKE', "%{$nombre}%");
    }

    /**
     * Scope: Filtrar por tipo de boleta
     */
    public function scopeByTipoBoleta($query, $tipoBoleta)
    {
        return $query->where('tipo_boleta', $tipoBoleta);
    }

    /**
     * Scope: Solo con teléfono
     */
    public function scopeConTelefono($query)
    {
        return $query->whereNotNull('telefono');
    }

    /**
     * Verificar si tiene teléfono definido
     */
    public function tieneTelefono(): bool
    {
        return !empty($this->telefono);
    }

    /**
     * Obtener teléfono formateado
     */
    public function getTelefonoFormateado(): string
    {
        return $this->telefono ?: 'Sin teléfono';
    }

    /**
     * Formato del nombre (primera letra mayúscula)
     */
    public function getNombreFormateadoAttribute(): string
    {
        return ucwords(strtolower($this->nombre));
    }

    /**
     * Obtener información completa del documento formateado
     */
    public function getDocumentoFormateadoAttribute(): string
    {
        return strtoupper($this->documento);
    }

    /**
     * Obtener nombre completo
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->nombres . ' ' . $this->apellidos;
    }

    /**
     * Relación many-to-many con Vehiculo a través de la tabla pivot
     */
    public function vehiculos()
    {
        return $this->belongsToMany(Vehiculo::class, 'vehiculo_propietario')
                    ->withPivot(['fecha_inicio', 'fecha_fin'])
                    ->withTimestamps();
    }

    /**
     * Relación con VehiculoPropietario (registros de la tabla pivot)
     */
    public function vehiculoPropietarios()
    {
        return $this->hasMany(VehiculoPropietario::class);
    }

    /**
     * Obtener vehículos activos (sin fecha de fin)
     */
    public function vehiculosActivos()
    {
        return $this->belongsToMany(Vehiculo::class, 'vehiculo_propietario')
                    ->wherePivotNull('fecha_fin')
                    ->withPivot(['fecha_inicio', 'fecha_fin'])
                    ->withTimestamps();
    }

    /**
     * Verificar si tiene vehículos asociados
     */
    public function tieneVehiculos(): bool
    {
        return $this->vehiculos()->count() > 0;
    }

    /**
     * Contar vehículos activos
     */
    public function cantidadVehiculosActivos(): int
    {
        return $this->vehiculosActivos()->count();
    }
}
