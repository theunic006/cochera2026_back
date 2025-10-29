<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'idrol',
        'id_company',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'idrol');
    }

    /**
     * Relación con Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    /**
     * Relación con permisos (muchos a muchos)
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id')
                    ->withTimestamps();
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions()->where('slug', $permissionSlug)->where('is_active', true)->exists();
    }

    /**
     * Verificar si el usuario tiene alguno de los permisos dados
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        return $this->permissions()->whereIn('slug', $permissionSlugs)->where('is_active', true)->exists();
    }

    /**
     * Verificar si el usuario tiene todos los permisos dados
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        $count = $this->permissions()->whereIn('slug', $permissionSlugs)->where('is_active', true)->count();
        return $count === count($permissionSlugs);
    }

    /**
     * Asignar un permiso al usuario
     */
    public function givePermission($permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }

        $this->permissions()->syncWithoutDetaching([$permission->id]);
    }

    /**
     * Revocar un permiso del usuario
     */
    public function revokePermission($permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }

        $this->permissions()->detach($permission->id);
    }

    /**
     * Sincronizar permisos del usuario
     */
    public function syncPermissions(array $permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
    }
}
