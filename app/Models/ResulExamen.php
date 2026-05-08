<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResulExamen extends Model
{
    protected $table = 'resul_examens';

    protected $fillable = [
        'id_orden',
        'nombre_paciente',
        'nombre_examen',
        'observaciones',
        'imagen_path',
        'bioanalista',
        'fecha_resultado',
    ];

    protected $casts = [
        'fecha_resultado' => 'datetime',
    ];
}
