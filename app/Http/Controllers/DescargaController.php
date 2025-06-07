<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContenidoTema;

use Illuminate\Support\Facades\Storage;

class DescargaController extends Controller
{
    public function descargarDocumento($id)
    {
        $contenido = ContenidoTema::findOrFail($id);

        $extension = pathinfo($contenido->ruta, PATHINFO_EXTENSION);
        $nombreConExtension = $contenido->nombre . '.' . $extension;

        return Storage::download($contenido->ruta, $nombreConExtension);
    }

    public function descargarVideo($id)
    {
        $contenido = ContenidoTema::findOrFail($id);
        return Storage::download($contenido->ruta, $contenido->nombre);
    }
}