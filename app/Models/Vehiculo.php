<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model
{
    use HasFactory;

    /**
     * Relación: Un vehículo tiene muchas observaciones
     */
    public function observaciones()
    {
        return $this->hasMany(\App\Models\Observacion::class, 'id_vehiculo', 'id');
    }
    /**
     * The table associated with the model.
     */
    protected $table = 'vehiculos';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'placa',
        'modelo',
        'marca',
        'color',
        'anio',
        'tipo_vehiculo_id',
        'frecuencia',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'anio' => 'integer',
        'tipo_vehiculo_id' => 'integer',
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

    // Relación con tipo de vehículo
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'tipo_vehiculo_id');
    }

    /**
     * Scope: Filtrar por placa
     */
    public function scopeByPlaca($query, $placa)
    {
        return $query->where('placa', 'LIKE', "%{$placa}%");
    }

    /**
     * Scope: Filtrar por marca
     */
    public function scopeByMarca($query, $marca)
    {
        return $query->where('marca', 'LIKE', "%{$marca}%");
    }

    /**
     * Scope: Filtrar por modelo
     */
    public function scopeByModelo($query, $modelo)
    {
        return $query->where('modelo', 'LIKE', "%{$modelo}%");
    }

    /**
     * Scope: Filtrar por color
     */
    public function scopeByColor($query, $color)
    {
        return $query->where('color', 'LIKE', "%{$color}%");
    }

    /**
     * Scope: Filtrar por tipo de vehículo
     */
    public function scopeByTipoVehiculo($query, $tipoId)
    {
        return $query->where('tipo_vehiculo_id', $tipoId);
    }

    /**
     * Formato de placa (mayúsculas)
     */
    public function getPlacaFormateadaAttribute(): string
    {
        return strtoupper($this->placa);
    }

    /**
     * Información completa del vehículo
     */
    public function getDescripcionCompletaAttribute(): string
    {
        $descripcion = $this->placa_formateada;

        if ($this->marca && $this->modelo) {
            $descripcion .= ' - ' . $this->marca . ' ' . $this->modelo;
        } elseif ($this->marca) {
            $descripcion .= ' - ' . $this->marca;
        } elseif ($this->modelo) {
            $descripcion .= ' - ' . $this->modelo;
        }

        if ($this->color) {
            $descripcion .= ' (' . $this->color . ')';
        }

        return $descripcion;
    }

    /**
     * Verificar si tiene tipo de vehículo asignado
     */
    public function tieneTipoVehiculo(): bool
    {
        return !is_null($this->tipo_vehiculo_id);
    }

    /**
     * Relación many-to-many con Propietario a través de la tabla pivot
     */
    public function propietarios()
    {
        return $this->belongsToMany(Propietario::class, 'vehiculo_propietario')
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
     * Obtener propietario actual (sin fecha de fin)
     */
    public function propietarioActual()
    {
        return $this->belongsToMany(Propietario::class, 'vehiculo_propietario')
                    ->wherePivotNull('fecha_fin')
                    ->withPivot(['fecha_inicio', 'fecha_fin'])
                    ->withTimestamps()
                    ->first();
    }

    /**
     * Obtener todos los propietarios activos
     */
    public function propietariosActivos()
    {
        return $this->belongsToMany(Propietario::class, 'vehiculo_propietario')
                    ->wherePivotNull('fecha_fin')
                    ->withPivot(['fecha_inicio', 'fecha_fin'])
                    ->withTimestamps();
    }

    /**
     * Verificar si tiene propietario actual
     */
    public function tienePropietario(): bool
    {
        return $this->propietarioActual() !== null;
    }

    /**
     * Obtener nombre del propietario actual
     */
    public function getNombrePropietarioActualAttribute(): ?string
    {
        $propietario = $this->propietarioActual();
        return $propietario ? $propietario->nombre_completo : null;
    }
}
