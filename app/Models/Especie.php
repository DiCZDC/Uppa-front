<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'nombre_cientifico',
        'nombre_comun',
        'familia',
        'zonas_crecimiento',
        'ambientes_comunes',
    ];
}
