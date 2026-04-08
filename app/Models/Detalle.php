<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    use HasFactory;

    protected $table = 'detalles';
    protected $primaryKey = 'id_detalles';

    protected $fillable = [
        'id_orden',
        'id_examen',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'id_orden', 'id_orden');
    }

    public function examen()
    {
        return $this->belongsTo(Examen::class, 'id_examen', 'id_examen');
    }
}
