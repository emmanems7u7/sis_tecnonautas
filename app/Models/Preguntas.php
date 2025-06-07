<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preguntas extends Model
{
    use HasFactory;
    
    protected $fillable = ['texto', 'tipo','evaluacion_id'];

    public function opciones()
    {
        return $this->hasMany(Opcion::class);
    }
 
   
    public function evaluacion()
{
    return $this->belongsTo(Evaluacion::class, 'id_e');
}
    public function respuestas()
{
    return $this->hasMany(Respuesta::class, 'pregunta_id'); 
}
public function opcionesCorrectas()
{
    return $this->hasMany(Opcion::class)->where('correcta', 1);
}

}
