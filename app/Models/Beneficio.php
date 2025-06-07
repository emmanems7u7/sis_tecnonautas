<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficio extends Model
{
    use HasFactory;
    protected $fillable = [

        'id_a',
        'beneficio',

    ];

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_a');
    }

}
