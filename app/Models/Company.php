<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'companies';

    /**
     * Estados disponibles para las companies
     */
    public const ESTADO_ACTIVO = 'activo';
    public const ESTADO_SUSPENDIDO = 'suspendido';
    public const ESTADO_INACTIVO = 'inactivo';
    public const ESTADO_PENDIENTE = 'pendiente';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nombre',
        'ubicacion',
        'logo',
        'capacidad',
        'descripcion',
        'estado',
        'imp_input',
        'imp_output',
        'ngrok',
        'ruc',
        'token'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
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
     * Relación con Users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_company');
    }

    /**
     * Obtener todos los estados disponibles
     */
    public static function getEstadosDisponibles(): array
    {
        return [
            self::ESTADO_ACTIVO,
            self::ESTADO_SUSPENDIDO,
            self::ESTADO_INACTIVO,
            self::ESTADO_PENDIENTE,
        ];
    }

    /**
     * Verificar si la company está activa
     */
    public function isActive(): bool
    {
        return $this->estado === self::ESTADO_ACTIVO;
    }

    /**
     * Verificar si la company está suspendida
     */
    public function isSuspended(): bool
    {
        return $this->estado === self::ESTADO_SUSPENDIDO;
    }

    /**
     * Activar la company
     */
    public function activate(): bool
    {
        return $this->update(['estado' => self::ESTADO_ACTIVO]);
    }

    /**
     * Suspender la company
     */
    public function suspend(): bool
    {
        return $this->update(['estado' => self::ESTADO_SUSPENDIDO]);
    }

    /**
     * Inactivar la company
     */
    public function deactivate(): bool
    {
        return $this->update(['estado' => self::ESTADO_INACTIVO]);
    }

    /**
     * Cambiar estado de la company
     */
    public function changeEstado(string $estado): bool
    {
        if (in_array($estado, self::getEstadosDisponibles())) {
            return $this->update(['estado' => $estado]);
        }

        return false;
    }

    /**
     * Scope para filtrar companies activas
     */
    public function scopeActive($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }

    /**
     * Scope para filtrar companies suspendidas
     */
    public function scopeSuspended($query)
    {
        return $query->where('estado', self::ESTADO_SUSPENDIDO);
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeByEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }
}
