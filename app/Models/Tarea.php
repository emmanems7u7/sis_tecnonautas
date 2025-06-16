<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;


    protected $fillable = [
        'nombre',
        'detalle',
        'ruta_archivo',
        'limite',
        'id_pm',
    ];
    public function paraleloModulo()
    {
        return $this->belongsTo(paralelo_modulo::class, 'id_pm');
    }

    public function tareasEstudiantes()
    {
        return $this->hasMany(tareas_estudiante::class, 'tareas_id');
    }


}
