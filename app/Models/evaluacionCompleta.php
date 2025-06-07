<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class evaluacionCompleta extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_u',
        'id_e',
        'completado',
        'nota',
    ];
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'id_e', 'id_evaluacion');
    }
    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'id_e');
    }

}
