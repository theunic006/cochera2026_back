<?php

namespace App\Models\Facturacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payload extends Model
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
    protected $table = 'payload';

    /**
     * Indica si el modelo debe usar timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_comprobante',
        'estado',
        'hash',
        'xml',
        'cdr',
        'ticket',
        'created_at',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relación con Comprobante
     */
    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'id_comprobante', 'id');
    }
}
