<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
    
       
    ];
    public function temasParamodulos()
    {
        return $this->hasMany(Temas_paramodulo::class, 'id_t');
    }

    public function contenidoTemas()
    {
        return $this->hasMany(ContenidoTema::class, 'id_t');
    }

   
}
