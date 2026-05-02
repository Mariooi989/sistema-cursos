<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Comentario extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'comentarios';
    protected $fillable = ['curso_id', 'usuario', 'comentario', 'calificacion'];
}