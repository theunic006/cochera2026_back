<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehiculoPropietario extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'vehiculo_propietario';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'vehiculo_id',
        'propietario_id',
        'fecha_inicio',
        'fecha_fin',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'vehiculo_id' => 'integer',
        'propietario_id' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Laravel timestamps enabled (created_at, updated_at)
     */
    public $timestamps = true;

    /**
     * Relación con Vehiculo
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Relación con Propietario
     */
    public function propietario()
    {
        return $this->belongsTo(Propietario::class);
    }

    /**
     * Scope: Relaciones activas (sin fecha de fin)
     */
    public function scopeActiva($query)
    {
        return $query->whereNull('fecha_fin');
    }

    /**
     * Scope: Relaciones finalizadas
     */
    public function scopeFinalizada($query)
    {
        return $query->whereNotNull('fecha_fin');
    }

    /**
     * Scope: Relaciones vigentes en una fecha específica
     */
    public function scopeVigenteEn($query, $fecha)
    {
        return $query->where('fecha_inicio', '<=', $fecha)
                    ->where(function ($q) use ($fecha) {
                        $q->whereNull('fecha_fin')
                          ->orWhere('fecha_fin', '>=', $fecha);
                    });
    }

    /**
     * Verificar si la relación está activa
     */
    public function esActiva(): bool
    {
        return is_null($this->fecha_fin);
    }

    /**
     * Calcular duración de la relación en días
     */
    public function getDuracionDiasAttribute(): ?int
    {
        if ($this->fecha_fin) {
            return $this->fecha_inicio->diffInDays($this->fecha_fin);
        }

        return $this->fecha_inicio->diffInDays(now());
    }

    /**
     * Obtener información completa de la relación
     */
    public function getDescripcionCompletaAttribute(): string
    {
        $descripcion = $this->vehiculo->placa . ' - ' . $this->propietario->nombre_completo;
        $descripcion .= ' (desde ' . $this->fecha_inicio->format('d/m/Y');

        if ($this->fecha_fin) {
            $descripcion .= ' hasta ' . $this->fecha_fin->format('d/m/Y') . ')';
        } else {
            $descripcion .= ' - ACTIVA)';
        }

        return $descripcion;
    }
}
