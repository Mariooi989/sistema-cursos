<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';

    protected $fillable = [
        'curso',
        'precio',
        'fecha_inicio',
        'nombre',
        'apellido',
        'email',
        'telefono',
        'edad',
        'dni',
        'metodo_pago',
        'estado_pago',
        'observaciones',
    ];
}