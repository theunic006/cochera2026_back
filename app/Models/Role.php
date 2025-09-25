<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'descripcion'
    ];

    /**
     * Relación con Users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'idrol');
    }
}
