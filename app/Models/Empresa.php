<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'nombre_empresa',
        'rif',
        'email',
        'telefono',
        'direccion',
        'porcentaje_descuento',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_empresa', 'id_empresa');
    }
}
