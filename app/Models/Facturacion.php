<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    use HasFactory;

    protected $table = 'facturacion';
    protected $primaryKey = 'id_facturacion';

    protected $fillable = [
        'id_orden',
        'fecha_emision',
        'subtotal',
        'monto_descuento',
        'monto_total',
        'tipo_pago',
        'estatus',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'id_orden', 'id_orden');
    }
}
