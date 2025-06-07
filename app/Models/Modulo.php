<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombreM',
        'Descripcion',
        'Duracion',
        'imagen',
        'portada',
        'ultimo_modulo'
    ];

    public function evalPorModulo()
    {
        return $this->hasMany(EvalPorModulo::class, 'id_m');
    }
    public function asigModulos()
    {
        return $this->hasMany(AsigModulo::class, 'id_m'); // 'id_m' es la columna de clave foránea en asigModulo
    }
    function paraleloModulos()
    {
        return $this->hasMany(paralelo_modulo::class, 'id_m');
    }
}
