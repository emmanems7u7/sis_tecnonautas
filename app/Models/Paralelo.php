<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paralelo extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'cupo',
    ];
    public function paraleloModulos()
    {
        return $this->hasMany(paralelo_modulo::class, 'id_p', 'id');
    }
}
