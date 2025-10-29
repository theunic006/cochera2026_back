<?php

namespace App\Models\Facturacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    /**
     * La conexión de base de datos que debe usar el modelo.
     *
     * @var string
     */
    protected $connection = 'factura2026';

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'clientes';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombres',
        'apepaterno',
        'apematerno',
        'razon_social',
        'email',
        'direccion',
        'telefono',
        'descripcion',
        'estado',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estado' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con Comprobantes
     */
    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class, 'id_cliente', 'id');
    }
}
