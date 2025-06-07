<?php

namespace App\Repositories;


use App\Interfaces\RespuestasInterface;
use App\Models\Opcion;
use App\Models\Preguntas;
use App\Models\Respuesta;
use Illuminate\Support\Facades\Auth;

class RespuestasRepository implements RespuestasInterface
{
    public $userId;

    public function __construct()
    {
        $this->userId = Auth::id();
    }
    public function list($evaluacion_id)
    {

    }
    public function storeRespuestas($request)
    {
        $this->userId = Auth::id();
        $respuestas = $request->input('respuestas', []);
        $evaluacion_id = $request->input('id_e');

        foreach ($respuestas as $preguntaId => $respuesta) {
            $pregunta = Preguntas::find($preguntaId); // Suponiendo que tienes un modelo llamado Preguntas

            if ($pregunta->tipo === 'parrafo') {
                // Respuesta de tipo "párrafo"
                Respuesta::create([
                    'pregunta_id' => $preguntaId,
                    'contenido' => $respuesta,
                    'opcion_id' => null,
                    'id_u' => $this->userId,
                    'id_e' => $evaluacion_id,
                    'correcta' => null,
                ]);
            } elseif (is_array($respuesta)) {

                $opcionesCorrectas = $pregunta->opcionesCorrectas->pluck('id')->toArray();


                $aciertos = count(array_intersect($opcionesCorrectas, $respuesta));


                $contenido = implode(', ', $respuesta);

                $valorCorrecta = $aciertos === count($opcionesCorrectas) ? 1 : ($aciertos > 0 ? 2 : 0);

                foreach ($respuesta as $opcionId) {
                    $cont = Opcion::find($opcionId);
                    Respuesta::create([
                        'pregunta_id' => $preguntaId,
                        'contenido' => $cont->texto, // Almacena las opciones seleccionadas como una cadena
                        'opcion_id' => $opcionId,
                        'id_u' => $this->userId,
                        'id_e' => $evaluacion_id,
                        'correcta' => $valorCorrecta, // Asigna un valor diferente en función de los aciertos
                        // Agrega cualquier otro dato que necesites almacenar en la respuesta
                    ]);
                }
            } else {
                // Respuesta de selección única
                $esCorrecta = in_array($respuesta, $pregunta->opcionesCorrectas->pluck('id')->toArray());
                $cont = Opcion::find($respuesta);
                Respuesta::create([
                    'pregunta_id' => $preguntaId,
                    'contenido' => $cont->texto, // No es necesario contenido para respuestas de selección única
                    'opcion_id' => $respuesta,
                    'id_u' => $this->userId,
                    'id_e' => $evaluacion_id,
                    'correcta' => $esCorrecta ? 1 : 0, // Marcar como correcta (1) o incorrecta (0) en el caso de selección única
                    // Agrega cualquier otro dato que necesites almacenar en la respuesta
                ]);
            }
        }
    }
    public function Nota($totalPreguntas, $preguntas)
    {
        $nota = 0;
        $promedioNota = 100 / $totalPreguntas;
        foreach ($preguntas as $pregunta) {
            $respuestasEst = Respuesta::select('respuestas.id', 'respuestas.pregunta_id', 'preguntas.tipo', 'respuestas.opcion_id', 'respuestas.contenido', 'respuestas.id_u', 'respuestas.id_e', 'respuestas.correcta')
                ->join('preguntas', 'respuestas.pregunta_id', '=', 'preguntas.id')
                ->where('id_u', $this->userId)->where('pregunta_id', $pregunta->id)->get();



            if ($respuestasEst[0]->correcta == 1) {
                $nota += $promedioNota;
            } else if ($respuestasEst[0]->correcta == 2) {
                $nota += ($promedioNota / 2);
            }

        }
        return round($nota, 2);
    }

    public function Nota2($id_u, $totalPreguntas, $preguntas)
    {
        $nota = 0;
        $promedioNota = 100 / $totalPreguntas;
        foreach ($preguntas as $pregunta) {
            $respuestasEst = Respuesta::select('respuestas.id', 'respuestas.pregunta_id', 'preguntas.tipo', 'respuestas.opcion_id', 'respuestas.contenido', 'respuestas.id_u', 'respuestas.id_e', 'respuestas.correcta')
                ->join('preguntas', 'respuestas.pregunta_id', '=', 'preguntas.id')
                ->where('id_u', $id_u)->where('pregunta_id', $pregunta->id)->get();



            if ($respuestasEst[0]->correcta == 1) {
                $nota += $promedioNota;
            } else if ($respuestasEst[0]->correcta == 2) {
                $nota += ($promedioNota / 2);
            }

        }
        return round($nota, 2);
    }
    public function listU($id, $evaluacion_id)
    {
        $respuestas = Respuesta::where('id_e', $evaluacion_id)
            ->where('id_u', $id)
            ->get();
        return $respuestas;
    }
}