<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsistenciaEstudiante extends Model
{
    use HasFactory;
    protected $fillable = [

        'user_id',
        'id_pm',
        'fecha',
        'asistencia',
    ];


}
