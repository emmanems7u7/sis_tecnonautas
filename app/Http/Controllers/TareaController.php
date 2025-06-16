<?php

namespace App\Http\Controllers;

use App\Models\asigModulo;
use Illuminate\Support\Facades\Auth;
use App\Models\Tarea;
use App\Models\paralelo_modulo;
use App\Models\tareas_estudiante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'nombre' => 'required|string|max:200',
            'detalle' => 'required|string',
            'ruta_archivo' => 'nullable|file',
            'limite' => 'required|date',
            'id_pm' => 'required|integer|exists:paralelo_modulos,id',
        ]);

        $tarea = new Tarea;
        $tarea->nombre = $request->nombre;
        $tarea->detalle = $request->detalle;

        if ($request->hasFile('ruta_archivo')) {
            $tarea->ruta_archivo = $request->file('ruta_archivo')->store('archivos', 'public');
        }

        $tarea->limite = $request->limite;
        $tarea->id_pm = $request->id_pm;
        $tarea->save();

        return redirect()->back()->with('success', 'Tarea creada exitosamente.');
    }

    // Método para mostrar el formulario de edición de una tarea
    public function edit($id)
    {
        $tarea = Tarea::findOrFail($id);
        return view('tareas.edit', compact('tarea'));
    }

    public function storeTarea(request $request)
    {

        $request->validate([
            'archivo' => 'required|file|mimes:pdf,doc,docx,jpg,png',
            'comentario' => 'nullable|string|max:1000',
            'tarea_id' => 'required|integer|exists:tareas,id',
        ]);

        $user = Auth::user();
        $archivo = $request->file('archivo');

        // Genera un nombre único con UUID y mantiene la extensión original
        $nombreArchivo = Str::uuid() . '.' . $archivo->getClientOriginalExtension();

        // Mueve el archivo directamente a public/archivos
        $archivo->move(public_path('archivos'), $nombreArchivo);

        // Guarda la ruta relativa para almacenar en BD
        $rutaArchivo = 'archivos/' . $nombreArchivo;


        $tareasAsignadas = tareas_estudiante::create([
            'archivo' => $rutaArchivo,
            'user_id' => $user->id,
            'comentario' => $request->comentario ? $request->comentario : '',
            'tareas_id' => $request->tarea_id,
            'nota' => 0,
        ]);
        return redirect()->back()->with('success', 'Tarea creada exitosamente.');

    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:200',
            'detalle' => 'required|string',
            'ruta_archivo' => 'nullable|file',
            'limite' => 'required|date',
            'id_pm' => 'required|integer|exists:paralelo_modulos,id',
        ]);

        $tarea = Tarea::findOrFail($id);
        $tarea->nombre = $request->nombre;
        $tarea->detalle = $request->detalle;

        if ($request->hasFile('ruta_archivo')) {
            if ($tarea->ruta_archivo) {
                Storage::disk('public')->delete($tarea->ruta_archivo);
            }
            $tarea->ruta_archivo = $request->file('ruta_archivo')->store('archivos', 'public');
        }

        $tarea->limite = $request->limite;
        $tarea->id_pm = $request->id_pm;
        $tarea->save();

        return redirect()->back()->with('success', 'Tarea actualizada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function showP($id_pm)
    {

        $tareas = Tarea::with('tareasEstudiantes.estudiantes')->where('id_pm', $id_pm)->get();

        $d = paralelo_modulo::find($id_pm);
        $id_a = asigModulo::where('id_m', $d->id_m)->first()->id_a;
        $id_m = $d->id_m;
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Modulos', 'url' => route('modulos.materia.show', ['id_n' => 0, 'id_a' => $id_a])],
        ];

        if (auth()->user()->hasRole(roles: 'admin') || auth()->user()->hasRole('profesor')) {
            $breadcrumb[] = ['name' => 'Paralelos', 'url' => route('Paralelos.modulos.show', ['id_m' => $id_m, 'id_a' => $id_a])];
        }

        $breadcrumb[] = ['name' => 'Contenido del módulo', 'url' => route('modulos.temas.show', ['id_pm' => $id_pm, 'id_m' => $id_m])];
        $breadcrumb[] = ['name' => 'Revisión de Tareas', 'url' => route('modulos.temas.show', ['id_pm' => $id_pm, 'id_m' => $id_m])];



        return view('tareas.showP', compact('breadcrumb', 'tareas', 'id_a', 'id_pm', 'id_m'));

    }
    public function showE($id_pm)
    {
        $userId = Auth::id();
        $d = paralelo_modulo::find($id_pm);
        $id_a = asigModulo::where('id_m', $d->id_m)->first()->id_a;
        $id_m = $d->id_m;
        $estudiante = User::with('tareasEstudiantes.tarea')
            ->find($userId);


        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Modulos', 'url' => route('modulos.materia.show', ['id_n' => 0, 'id_a' => $id_a])],
            ['name' => 'Contenido del módulo', 'url' => route('modulos.temas.show', ['id_pm' => $id_pm, 'id_m' => $id_m])],
            ['name' => 'Tareas Enviadas', 'url' => route('modulos.temas.show', ['id_pm' => $id_pm, 'id_m' => $id_m])],
        ];

        return view('tareas.showP', compact('breadcrumb', 'estudiante', 'id_a', 'id_pm', 'id_m'));
    }
    public function calificar(Request $request, $id)
    {
        $tarea = tareas_estudiante::find($id);
        $tarea->nota = $request->nota;
        $tarea->save();
        return redirect()->back()->with('success', 'Tarea calificada exitosamente.');

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarea $tarea)
    {
        //
    }
}
