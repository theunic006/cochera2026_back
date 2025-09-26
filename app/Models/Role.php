<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Estados disponibles para los roles
     */
    public const ESTADO_ACTIVO = 'activo';
    public const ESTADO_SUSPENDIDO = 'suspendido';
    public const ESTADO_INACTIVO = 'inactivo';

    protected $table = 'roles';

    protected $fillable = [
        'descripcion',
        'estado'
    ];

    /**
     * Relación con Users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'idrol');
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
        ];
    }

    /**
     * Verificar si el rol está activo
     */
    public function isActive(): bool
    {
        return $this->estado === self::ESTADO_ACTIVO;
    }

    /**
     * Verificar si el rol está suspendido
     */
    public function isSuspended(): bool
    {
        return $this->estado === self::ESTADO_SUSPENDIDO;
    }

    /**
     * Activar el rol
     */
    public function activate(): bool
    {
        return $this->update(['estado' => self::ESTADO_ACTIVO]);
    }

    /**
     * Suspender el rol
     */
    public function suspend(): bool
    {
        return $this->update(['estado' => self::ESTADO_SUSPENDIDO]);
    }

    /**
     * Scope para filtrar roles activos
     */
    public function scopeActive($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVO);
    }

    /**
     * Scope para filtrar roles suspendidos
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

    /**
     * Obtener el conteo de usuarios por rol
     */
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }
}
