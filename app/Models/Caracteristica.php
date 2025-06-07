<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    use HasFactory;
    protected $fillable = [

        'id_a',
        'caracteristica',

    ];
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_a');
    }

}
