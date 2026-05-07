<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'ordenes';
    protected $primaryKey = 'id_orden';

    protected $fillable = [
        'fecha',
        'total_monto',
        'id_usuario',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function facturacion()
    {
        return $this->hasOne(Facturacion::class, 'id_orden', 'id_orden');
    }

    public function detalles()
    {
        return $this->hasMany(Detalle::class, 'id_orden', 'id_orden');
    }
}
