<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContenidoTema extends Model
{
    use HasFactory;
    protected $fillable = ['id_t','nombre', 'tipo', 'ruta'];

    // Define las constantes para los tipos de recurso
    const TIPO_DOCUMENTO = 'documento';
    const TIPO_VIDEO = 'video';
    const TIPO_ENLACE = 'enlace';

    public function getUrl()
    {
        if ($this->tipo === self::TIPO_DOCUMENTO) {
            return asset('storage/' . $this->ruta);
        } elseif ($this->tipo === self::TIPO_VIDEO) {
            // Define cómo obtener la URL del video si es necesario
        } elseif ($this->tipo === self::TIPO_ENLACE) {
            return $this->ruta;
        }

        return null;
    }
    public function tema()
    {
        return $this->belongsTo(Tema::class, 'id_t');
    }
}
