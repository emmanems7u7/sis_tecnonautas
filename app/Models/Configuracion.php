<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $fillable = [
        'doble_factor_autenticacion',
        'limite_de_sesiones',
        'GROQ_API_KEY',
        'mantenimiento'

    ];

    protected $table = 'configuracion';
    protected $guarded = [];
}
