<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    use HasFactory;
    protected $fillable = ['texto', 'pregunta_id','correcta'];

    public function pregunta()
    {
        return $this->belongsTo(Preguntas::class);
    }
}
