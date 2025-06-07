<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asigModulo extends Model
{
    use HasFactory;
    protected $fillable = [

        'id_a',
        'id_m',

    ];
    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'id_m'); // 'id_m' es el nombre de la columna que referencia al modulo
    }
}
