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
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nombre',
        'ubicacion',
        'logo',
        'descripcion',
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
}
