<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContenidoTema;
use App\Traits\Base64ToFile;
use DB;
class ContenidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_t)
    {

        return view('contenidos.create', ['id_t' => $id_t]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'nombrecontenido' => 'required|string|max:255',
                'id_t' => 'required|integer|exists:temas,id',
                'documento' => 'nullable|string',
                'video' => 'nullable|string',
                'enlace' => 'nullable|string|url|max:255',

            ], [
                'nombrecontenido.required' => 'El nombre del contenido es obligatorio.',
                'nombrecontenido.string' => 'El nombre del contenido debe ser un texto válido.',
                'nombrecontenido.max' => 'El nombre del contenido no puede superar los 255 caracteres.',

                'id_t.required' => 'El ID del tema es obligatorio.',
                'id_t.integer' => 'El ID del tema debe ser un número entero.',
                'id_t.exists' => 'El ID del tema no existe en la base de datos.',

                'documento.string' => 'El documento debe ser un texto válido.',
                'video.string' => 'El video debe ser un texto válido.',
                'enlace.string' => 'El enlace debe ser un texto válido.',
                'enlace.url' => 'El enlace debe ser una URL válida.',
                'enlace.max' => 'El enlace no puede tener más de 255 caracteres.',
            ]);



            $id_t = $request['id_t'];

            $nombre = $request['nombrecontenido'];
            if ($request->hasFile('documento')) {
                $documento = $request->file('documento');

                $rutaDocumento = $documento->store('documentos');
                ContenidoTema::create([
                    'nombre' => $nombre,
                    'tipo' => 'documento',
                    'ruta' => $rutaDocumento,
                    'id_t' => $id_t,
                ]);
            }

            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $nombreVideo = $video->getClientOriginalName();
                $rutaVideo = $video->store('videos');
                ContenidoTema::create([
                    'nombre' => $nombre,
                    'tipo' => 'video',
                    'ruta' => $rutaVideo,
                    'id_t' => $id_t,
                ]);
            }

            if ($request->filled('enlace')) {
                $nombreEnlace = $request->input('enlace');
                ContenidoTema::create([
                    'nombre' => $nombre,
                    'tipo' => 'enlace',
                    'ruta' => $nombreEnlace,
                    'id_t' => $id_t,
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Contenido Cargado correctamente']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $contenido = ContenidoTema::findOrFail($id);
            // Realiza cualquier validación o lógica adicional antes de eliminar si es necesario

            $contenido->delete();

            return redirect()->back()->with('status', 'Contenido eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el contenido');
        }
    }
}
