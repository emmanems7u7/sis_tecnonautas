<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paralelo_modulo extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_p',
        'id_m',
        'activo',
        'inscritos',
        'mes',
    ];
    public function temasParamodulos()
    {
        return $this->hasMany(Temas_paramodulo::class, 'id_pm');
    }
    public function paralelo()
    {
        return $this->belongsTo(Paralelo::class, 'id_p', 'id');
    }
    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'id_m', 'id');
    }
    public function estudiantesAsignacionParamodulos()
    {
        return $this->hasMany(Estudiantes_asignacion_paramodulo::class, 'id_pm', 'id');
    }

}
