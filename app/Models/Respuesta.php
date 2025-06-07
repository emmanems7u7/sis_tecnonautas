<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;
    protected $fillable = ['pregunta_id','opcion_id', 'contenido','correcta','id_u','id_e'];

    public function pregunta()
    {
        return $this->belongsTo(Preguntas::class);
    }
    public function opcion()
{
    return $this->belongsTo(Opcion::class, 'opcion_id');
}
}
