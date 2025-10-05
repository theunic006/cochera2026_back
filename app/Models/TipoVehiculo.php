<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoVehiculo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'tipo_vehiculos';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_empresa',
        'nombre',
        'valor',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'valor' => 'float',
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
     * Scope: Solo tipos con valor definido
     */
    public function scopeConValor($query)
    {
        return $query->whereNotNull('valor');
    }

    /**
     * Scope: Filtrar por rango de valores
     */
    public function scopeByRangoValor($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('valor', '>=', $min);
        }
        if ($max !== null) {
            $query->where('valor', '<=', $max);
        }
        return $query;
    }

    /**
     * Verificar si tiene valor definido
     */
    public function tieneValor(): bool
    {
        return $this->valor !== null;
    }

    /**
     * Obtener valor formateado
     */
    public function getValorFormateado(): string
    {
        return $this->valor ? number_format($this->valor, 2) : 'Sin valor';
    }

    /**
     * Formato del nombre (primera letra mayúscula)
     */
    public function getNombreFormateadoAttribute(): string
    {
        return ucfirst(strtolower($this->nombre));
    }

    /**
     * Relación con Vehiculos
     */
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }

    /**
     * Relación con Tolerancia (uno a uno)
     */
    public function tolerancia()
    {
        return $this->hasOne(Tolerancia::class);
    }

    /**
     * Verificar si tiene tolerancia definida
     */
    public function tieneTolerancia(): bool
    {
        return $this->tolerancia !== null;
    }

    /**
     * Obtener minutos de tolerancia o valor por defecto
     */
    public function getMinutosTolerancia($default = 0): int
    {
        return $this->tolerancia ? $this->tolerancia->minutos : $default;
    }
}
