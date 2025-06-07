<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'detalle',
        'creado',
        'limite',
        'completado',
        'publicado',
        'id_pm'
    ];

    public function preguntas()
    {
        return $this->hasMany(Preguntas::class);
    }
    public function preguntasid()
    {
        return $this->hasMany(Preguntas::class, 'id_e');
    }
    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'eval_por_modulos', 'id_e', 'id_m');
    }
    public function evaluacionesCompletas()
    {
        return $this->hasMany(EvaluacionCompleta::class, 'id_e');
    }

}
