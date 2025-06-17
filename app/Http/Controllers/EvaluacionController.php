<?php

namespace App\Http\Controllers;

use App\Interfaces\EvaluacionInterface;
use App\Interfaces\PreguntasInterface;
use App\Interfaces\RespuestasInterface;
use App\Interfaces\TareasInterface;

use App\Models\Estudiantes_asignacion_paramodulo;

use App\Models\Evaluacion;
use App\Models\Tarea;
use App\Models\evaluacionCompleta;

use App\Models\Respuesta;

use App\Models\paralelo_modulo;

use App\Models\asigModulo;

use Illuminate\Http\Request;

use DB;

class EvaluacionController extends Controller
{
    protected $preguntasInterface;
    protected $evaluacionInterface;
    protected $respuestasInterface;
    protected $TareasInterface;
    public function __construct(
        PreguntasInterface $preguntasInterface,
        EvaluacionInterface $EvaluacionInterface,
        RespuestasInterface $RespuestasInterface,
        TareasInterface $TareasInterface,


    ) {
        $this->preguntasInterface = $preguntasInterface;
        $this->evaluacionInterface = $EvaluacionInterface;
        $this->respuestasInterface = $RespuestasInterface;
        $this->TareasInterface = $TareasInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function estudianteseval($id_m, $id_a)
    {


        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Modulos', 'url' => route('modulos.materia.show', ['id_n' => 0, 'id_a' => $id_a])],
            ['name' => 'Evaluaciones', 'url' => route('home')],
        ];
        $evaluacion = Evaluacion::find($id_m);
        // dd($evaluacion);
        if ($evaluacion->completado == 'si') {
            return redirect()->back()->with('error', 'Evaluación ya completada.');
        }
        $paralelo_modulo = paralelo_modulo::where('id', $evaluacion->id_pm)->first();


        $id_pm = $paralelo_modulo->id;
        $id_mod = $paralelo_modulo->id_m;

        $preguntas = $this->preguntasInterface->list($id_m);
        return view('evaluacion.estudiantes', ['id_m' => $id_m], compact('breadcrumb', 'preguntas', 'id_pm', 'id_mod'));

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_e, $id_pm, $id_m)
    {


        $evaluacion = Evaluacion::find($id_e);
        $preguntas = $this->preguntasInterface->list($id_e);
        $d = paralelo_modulo::find($id_pm);
        $id_a = asigModulo::where('id_m', $d->id_m)->first()->id_a;

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Modulos', 'url' => route('modulos.materia.show', ['id_n' => 0, 'id_a' => $id_a])],
            ['name' => 'paralelos', 'url' => route('Paralelos.modulos.show', ['id_m' => $id_m, 'id_a' => $id_a])],
            ['name' => 'Contenido del modulo', 'url' => route('modulos.temas.show', ['id_pm' => $id_pm, 'id_m' => $id_m])],
            ['name' => $evaluacion->nombre, 'url' => route('modulos.temas.show', ['id_pm' => $id_pm, 'id_m' => $id_m])],

        ];

        return view(
            'evaluacion.create',
            compact(
                'preguntas',
                'evaluacion',
                'id_e',
                'id_pm',
                'id_m',
                'id_a',
                'breadcrumb'
            )
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        $messages = [
            'id_pm.required' => 'El campo id_pm es obligatorio.',
            'id_pm.integer' => 'El campo id_pm debe ser un número entero.',
            'id_pm.exists' => 'El id_pm no existe en la base de datos.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe superar los 255 caracteres.',
            'detalle.required' => 'El detalle es obligatorio.',
            'detalle.string' => 'El detalle debe ser texto.',
            'detalle.max' => 'El detalle no debe superar los 255 caracteres.',
            'limite.required' => 'La fecha límite es obligatoria.',
            'limite.date_format' => 'La fecha límite debe tener el formato correcto.',
        ];
        session(['modal' => 'crearEvaluacionModal', 'abrir' => true]);

        $validated = $request->validate([
            'id_pm' => 'required|integer|exists:paralelo_modulos,id',
            'nombre' => 'required|string|max:255',
            'detalle' => 'required|string|max:255',
            'limite' => 'required|date_format:Y-m-d\TH:i',
        ], $messages);

        session(['modal' => 'crearEvaluacionModal', 'abrir' => false]);
        $id_pm = $request->input('id_pm');
        $evaluacion = Evaluacion::create([
            'nombre' => $request->input('nombre'),
            'detalle' => $request->input('detalle'),
            'creado' => now(),
            'limite' => $request->input('limite'),
            'completado' => 'x',
            'id_pm' => $id_pm
        ]);

        $estudiantes = Estudiantes_asignacion_paramodulo::where('id_pm', $id_pm)->get();
        foreach ($estudiantes as $estudiante) {

            $evalEst = evaluacionCompleta::create([
                'id_u' => $estudiante->id_u,
                'id_e' => $evaluacion->id,
                'completado' => 'no',
                'nota' => 0,
            ]);
        }




        return redirect()->back()->with('success', 'Evaluación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function show(Evaluacion $evaluacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Evaluacion $evaluacion)
    {
        //
    }
    public function notasEstudiantes($id_a, $id_pm, $id_p)
    {


        $estudiantesEvaluaciones = $this->evaluacionInterface->GetEvaluacionesEstudiantes($id_p);

        $estudiantesTareas = $this->TareasInterface->GetTareasEstudiantes($id_p);

        // Si no hay tareas, inicializa un array vacío o con valores predeterminados.
        if (empty($estudiantesTareas)) {
            // Si no hay tareas, asignamos solo las evaluaciones a los estudiantes.
            $estudiantesTareas = array_map(function ($evaluacion) {
                return [
                    'user_id' => $evaluacion['user_id'],
                    'estudiante' => $evaluacion['estudiante'],
                    'evaluaciones' => $evaluacion['evaluaciones'], // Asocia las evaluaciones directamente.
                    'tareas' => [] // Asigna un array vacío de tareas.
                ];
            }, $estudiantesEvaluaciones);
        } else {
            // Si hay tareas, pero no evaluaciones, las tareas se asignan sin evaluaciones.
            if (empty($estudiantesEvaluaciones)) {
                $estudiantesTareas = array_map(function ($tarea) {
                    return [
                        'user_id' => $tarea['user_id'],
                        'estudiante' => $tarea['estudiante'],
                        'evaluaciones' => [], // Asigna un array vacío de evaluaciones.
                        'tareas' => $tarea['tareas'], // Asocia las tareas directamente.
                    ];
                }, $estudiantesTareas);
            } else {
                // Si hay tareas y evaluaciones, combinamos ambos.
                foreach ($estudiantesEvaluaciones as $evaluacion) {
                    foreach ($estudiantesTareas as &$estudiante) {
                        if ($estudiante['user_id'] == $evaluacion['user_id']) {
                            $estudiante['evaluaciones'] = $evaluacion['evaluaciones'];
                            break;
                        }
                    }
                }
            }
        }

        $tareas = Tarea::where('id_pm', $id_p)->get();
        $Evaluaciones = Evaluacion::where('id_pm', $id_p)->get();

        $data = [
            'estudiantesTareas' => $estudiantesTareas,
            'tareas' => $tareas,
            'evaluaciones' => $Evaluaciones,
        ];

        return response()->json($data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function publicar($id_e)
    {
        $evaluacion = Evaluacion::find($id_e);
        $evaluacion->publicado = 1;

        $evaluacion->save();


        return response()->json(['status' => 'success', 'message' => 'El examen ha sido publicado con éxito.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evaluacion $evaluacion)
    {
        //
    }
    public function Revision($id, $id_e)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Revision de Evaluacion', 'url' => route('home')],
        ];
        $respuestas = $this->respuestasInterface->listU($id, $id_e);
        $evaluacion = $this->evaluacionInterface->listarEvaluacion($id_e);
        $id_u = $id;

        return view('evaluacion.revision', compact('breadcrumb', 'id_u', 'id_e', 'respuestas', 'evaluacion'));
    }
    public function respIncorrecta($id_u, $id_p)
    {
        return redirect()->back()->with('status', 'seleccion incorrecta');
    }
    public function respCorrecta($id_u, $id_p, $id_e)
    {
        $respuesta = Respuesta::where('pregunta_id', $id_p)
            ->where('id_u', $id_u)
            ->first();

        if ($respuesta) {

            $respuesta->correcta = 1;
            $respuesta->save();
        }

        $totalPreguntas = $this->preguntasInterface->CountPreguntas($id_e);
        $preguntas = $this->preguntasInterface->getPreguntas($id_e);
        $nota = $this->respuestasInterface->Nota2($id_u, $totalPreguntas, $preguntas);
        $this->evaluacionInterface->GuardarEvaluacion($id_e, $id_u, $nota);

        return redirect()->back()->with('status', 'seleccion correcta');
    }

    function delete(Evaluacion $evaluacion, $id_pm, $id_m)
    {
        $evaluacion->delete();
        return redirect()->route('modulos.temas.show', ['id_pm' => $id_pm, 'id_m' => $id_m])->with('success', 'Evaluacion eliminada exitosamente.');
    }

}
