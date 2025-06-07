<?php

namespace App\Http\Controllers;

use App\Interfaces\EvaluacionInterface;
use App\Interfaces\PreguntasInterface;
use App\Interfaces\RespuestasInterface;
use App\Models\asigModulo;
use App\Models\Estudiantes_asignacion_paramodulo;
use App\Models\evaluacionCompleta;
use App\Models\Opcion;
use App\Models\Preguntas;
use App\Models\Respuesta;
use App\Models\Evaluacion;
use App\Models\paralelo_modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\Pregunta;
class RespuestasPreguntaController extends Controller
{
    protected $preguntasInterface;
    protected $respuestasInterface;
    protected $evaluacionInterface;

    public function __construct(PreguntasInterface $preguntasInterface, RespuestasInterface $respuestasInterface, EvaluacionInterface $evaluacionInterface)
    {
        $this->preguntasInterface = $preguntasInterface;
        $this->respuestasInterface = $respuestasInterface;
        $this->evaluacionInterface = $evaluacionInterface; // Corrige el signo de dólar extra

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
    public function store(Request $request, $id_pm, $id_m)
    {

        $preguntas = Preguntas::where('evaluacion_id', $request->input('id_e'))->get();


        $request->validate([
            'respuestas' => 'required|array',
            'respuestas.*' => 'required',
            'id_e' => 'required|exists:evaluacions,id',
        ], [
            'respuestas.required' => 'Debes responder todas las preguntas.',
            'respuestas.array' => 'Las respuestas deben ser un arreglo válido.',
            'respuestas.*.required' => 'No puedes dejar ninguna pregunta sin responder.',
            'id_e.required' => 'El identificador de la evaluación es necesario.',
            'id_e.exists' => 'La evaluación seleccionada no existe. Por favor, revisa e intenta nuevamente.',
        ]);

        $id_e = $request->input('id_e');


        $user_id = Auth::id();


        $evaluacion = evaluacionCompleta::where('id_e', $id_e)->where('id_u', $user_id)->first();
        if ($evaluacion) {
            if ($evaluacion->completado == 'si') {
                return redirect()->route('modulos.temas.show', ['id_pm' => $id_pm, 'id_m' => $id_m])->with('error', 'Evaluación ya completada.');
            }
        }



        $this->respuestasInterface->storeRespuestas($request);


        $totalPreguntas = $this->preguntasInterface->CountPreguntas(($id_e));
        $preguntas = $this->preguntasInterface->getPreguntas(($id_e));

        $nota = $this->respuestasInterface->Nota($totalPreguntas, $preguntas);

        $this->evaluacionInterface->GuardarEvaluacion($id_e, $user_id, nota: $nota);


        foreach ($preguntas as $pregunta) {

            if (!array_key_exists($pregunta->id, $request->input('respuestas'))) {
                // Si falta una respuesta para esta pregunta, se agrega un error
                return back()->withErrors([
                    'respuestas' => 'Falta responder la pregunta:' . $pregunta->texto . '.'
                ])->withInput();
            }
        }






        return redirect()->route('listarExamen', ['id_e' => $id_e]);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function show(Tarea $tarea)
    {
        //
    }
    public function listarPreguntasRespuestas($id_e)
    {

        $id = Auth::id();
        $respuestas = $this->respuestasInterface->listU($id, $id_e);
        $evaluacion = $this->evaluacionInterface->listarEvaluacion($id_e);
        $evaluacion = Evaluacion::find($id_e);
        $estudiante_asigparaMod = Estudiantes_asignacion_paramodulo::where('id_pm', $evaluacion->id_pm)->where('id_u', $id)->first();
        $id_a = $estudiante_asigparaMod->id_a;
        $id_pm = $evaluacion->id_pm;

        $id_m = paralelo_modulo::find($id_pm)->id_m;

        $evaluacion_estudiante = evaluacionCompleta::where('id_e', $id_e)->where('id_u', $id)->first();

        $nota = $evaluacion_estudiante->nota ?? 0;



        return view('evaluacion.show', compact('respuestas', 'evaluacion', 'nota', 'id_a', 'id_pm', 'id_m'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarea $tarea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarea $tarea)
    {
        //
    }

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
