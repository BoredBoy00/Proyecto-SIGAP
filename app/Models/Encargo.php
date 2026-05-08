<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encargo extends Model
{
    protected $table = 'encargos';

    protected $fillable = [
        'id_orden',
        'nombre_paciente',
        'examenes',
        'estado',
        'creado_por',
    ];
}
