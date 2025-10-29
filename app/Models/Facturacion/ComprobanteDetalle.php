<?php

namespace App\Models\Facturacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteDetalle extends Model
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
    protected $table = 'comprobante_detalle';

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
        'id_producto',
        'item',
        'codigo_producto',
        'descripcion',
        'familia',
        'unidad_medida',
        'cantidad',
        'precio_unitario',
        'valor_unitario',
        'descuento',
        'subtotal',
        'igv',
        'total',
        'tipo_igv',
        'placa',
        'orden_compra',
        'numero_contrato',
        'numero_guia',
        'observaciones',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cantidad' => 'decimal:4',
        'precio_unitario' => 'decimal:6',
        'valor_unitario' => 'decimal:6',
        'descuento' => 'decimal:6',
        'subtotal' => 'decimal:6',
        'igv' => 'decimal:6',
        'total' => 'decimal:6',
        'created_at' => 'datetime',
    ];
}
