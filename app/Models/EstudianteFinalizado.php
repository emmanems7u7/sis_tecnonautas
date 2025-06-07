<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudianteFinalizado extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'id_a',
        'nota',
        'aprobado',
    ];
}
