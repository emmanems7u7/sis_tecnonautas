<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;


    protected $fillable = [

        'nombre',
        'descripcion',
        'descripcionCorta',
        'tipo',
        'imagen1',
        'portada',
        'costo',
    ];
    public function estudiantesAsignacionParamodulos()
    {
        return $this->hasMany(Estudiantes_asignacion_paramodulo::class, 'id_a', 'id');
    }
    public function beneficios()
    {
        return $this->hasMany(Beneficio::class, 'id_a');
    }

    public function caracteristicas()
    {
        return $this->hasMany(Caracteristica::class, 'id_a');
    }

    public function objetivos()
    {
        return $this->hasMany(Objetivo::class, 'id_a');
    }


}
