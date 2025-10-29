<?php

namespace App\Models\Facturacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
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
    protected $table = 'comprobantes';

    protected $fillable = [
        'id_empresa',
        'id_cliente',
        'tipo_comprobante',
        'serie',
        'correlativo',
        'fecha_emision',
        'fecha_vencimiento',
        'condicion_pago',
        'moneda',
        'tipo_operacion',
        'gravadas',
        'exoneradas',
        'inafectas',
        'operaciones_gratuitas',
        'operaciones_exportadas',
        'igv',
        'total',
        'monto_descuento',
        'monto_subtotal',
        'monto_isc',
        'total_adelantos',
        'otros_cargos',
        'sumatoria_icbper',
        'monto_detracciones',
        'monto_percepciones',
        'monto_retenciones',
        'monto_descuento_igv',
        'hash_cpe',
        'xml_content',
        'cdr_content',
        'estado_sunat',
        'codigo_sunat',
        'mensaje_sunat',
        'fecha_envio_sunat',
        'estado',
        'motivo_anulacion',
        'metodo_pago',
        'observaciones',
        'serie_documento_destino',
        'correlativo_documento_destino',
        'fecha_emision_destino',
        'creado_en',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'fecha_envio_sunat' => 'datetime',
        'fecha_emision_destino' => 'date',
        'gravadas' => 'decimal:6',
        'exoneradas' => 'decimal:6',
        'inafectas' => 'decimal:6',
        'operaciones_gratuitas' => 'decimal:6',
        'operaciones_exportadas' => 'decimal:6',
        'igv' => 'decimal:6',
        'total' => 'decimal:6',
        'monto_descuento' => 'decimal:6',
        'monto_subtotal' => 'decimal:6',
        'monto_isc' => 'decimal:6',
        'total_adelantos' => 'decimal:6',
        'otros_cargos' => 'decimal:6',
        'sumatoria_icbper' => 'decimal:6',
        'monto_detracciones' => 'decimal:6',
        'monto_percepciones' => 'decimal:6',
        'monto_retenciones' => 'decimal:6',
        'monto_descuento_igv' => 'decimal:6',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con Cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }

    /**
     * Relación con Empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id');
    }

    /**
     * Relación con Detalles del Comprobante
     */
    public function detalles()
    {
        return $this->hasMany(ComprobanteDetalle::class, 'id_comprobante', 'id');
    }

    /**
     * Relación con Payload (respuesta de SUNAT)
     */
    public function payload()
    {
        return $this->hasOne(Payload::class, 'id_comprobante', 'id');
    }
}
