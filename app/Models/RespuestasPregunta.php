<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestasPregunta extends Model
{
    use HasFactory;
    protected $fillable = [
    'id_p',
   'id_p',
   'pregunta',
   'correcta',
   'marcaest',
    ];
}
