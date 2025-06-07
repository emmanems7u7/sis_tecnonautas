<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temas_paramodulo extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_t',
        'id_pm',
       
    ];

        public function paraleloModulo()
        {
            return $this->belongsTo(Paralelo_Modulo::class, 'id_pm');
        }

        public function tema()
        {
            return $this->belongsTo(Tema::class, 'id_t');
        }

        public function contenidoTemas()
        {
            return $this->hasMany(ContenidoTema::class, 'id_t', 'id_t');
        }
}
