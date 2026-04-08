<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $table = 'examenes';
    protected $primaryKey = 'id_examen';

    protected $fillable = [
        'codigo_examen',
        'nombre_examen',
        'precio_actual',
    ];

    public function detalles()
    {
        return $this->hasMany(Detalle::class, 'id_examen', 'id_examen');
    }
}
