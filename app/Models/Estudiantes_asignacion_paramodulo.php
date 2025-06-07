<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Estudiantes_asignacion_paramodulo extends Model
{
    use HasFactory, HasRoles;
    protected $fillable = [
        // se debera realizar el cambio de nombre de tabla para una nueva migracion
        // se generara error debido a modificacion
        'id_u',
        'id_a',
        'id_pm',
        'activo',
        'nota',
    ];

    // En el modelo Estudiantes_asignacion_paramodulo
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_a', 'id');
    }
    public function paraleloModulo()
    {
        return $this->belongsTo(paralelo_modulo::class, 'id_pm', 'id');
    }


    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_u');
    }

}
