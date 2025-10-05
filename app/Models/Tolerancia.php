<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tolerancia extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'tolerancia';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_empresa',
        'minutos',
        'descripcion',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'minutos' => 'integer',
        'descripcion' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Laravel timestamps enabled (created_at, updated_at)
     */
    public $timestamps = true;

    // Relación con TipoVehiculo removida - Tolerancia ahora es independiente

    /**
     * Scope: Filtrar por descripción
     */
    public function scopeByDescripcion($query, $descripcion)
    {
        return $query->where('descripcion', 'like', '%' . $descripcion . '%');
    }

    /**
     * Scope: Filtrar por minutos de tolerancia
     */
    public function scopeByMinutos($query, $minutos)
    {
        return $query->where('minutos', $minutos);
    }

    /**
     * Scope: Tolerancias mayores a ciertos minutos
     */
    public function scopeMayorA($query, $minutos)
    {
        return $query->where('minutos', '>', $minutos);
    }

    /**
     * Scope: Tolerancias menores a ciertos minutos
     */
    public function scopeMenorA($query, $minutos)
    {
        return $query->where('minutos', '<', $minutos);
    }

    /**
     * Formatear minutos para mostrar
     */
    public function getMinutosFormateadosAttribute(): string
    {
        if ($this->minutos == 1) {
            return '1 minuto';
        }

        return $this->minutos . ' minutos';
    }

    /**
     * Convertir minutos a horas y minutos
     */
    public function getTiempoFormateadoAttribute(): string
    {
        $horas = intval($this->minutos / 60);
        $minutosRestantes = $this->minutos % 60;

        if ($horas > 0) {
            if ($minutosRestantes > 0) {
                return $horas . 'h ' . $minutosRestantes . 'm';
            }
            return $horas . 'h';
        }

        return $this->minutos_formateados;
    }

    /**
     * Descripción completa de la tolerancia
     */
    public function getDescripcionCompletaAttribute(): string
    {
        return $this->descripcion . ': ' . $this->tiempo_formateado;
    }
}
