<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActaVotos extends Model
{
    protected $table = 'ActaVotos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'cantidadVotosTotal', 
        'votosNulos', 
        'votosBlancos', 
        'imagen', 
        'hora',
        'fecha',
    ];
}
