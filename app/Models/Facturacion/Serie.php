<?php

namespace App\Models\Facturacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
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
    protected $table = 'series';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_empresa',
        'tipo_comprobante',
        'serie',
        'correlativo_actual',
        'estado',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'correlativo_actual' => 'integer',
        'estado' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con Empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id');
    }

    /**
     * Relación con Comprobantes
     */
    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class, 'serie', 'serie');
    }
}
