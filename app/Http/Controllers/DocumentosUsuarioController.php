<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentosUsuario;
class DocumentosUsuarioController extends Controller
{

    public function subirDesdePerfil(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,bmp,webp|max:10240',
        ]);

        $file = $request->file('archivo');

        // Crear el registro sin ruta para obtener el id
        $documento = DocumentosUsuario::create([
            'user_id' => auth()->id(),
            'ruta' => '',
        ]);

        // Obtener extensión original
        $extension = $file->getClientOriginalExtension();


        $nombre = 'documento_' . $documento->id . '.' . $extension;


        $file->move(public_path('documentos_usuarios'), $nombre);

        // Actualizar ruta
        $documento->ruta = 'documentos_usuarios/' . $nombre;
        $documento->save();

        return redirect()->route('perfil')->with('success', 'Documento subido correctamente.');
    }
}
