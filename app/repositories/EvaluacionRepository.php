<?php

namespace App\Repositories;

use App\Models\Evaluacion;

use App\Models\User;
use App\Models\evaluacionCompleta;
use App\Interfaces\EvaluacionInterface;
use Illuminate\Support\Facades\Auth;

class EvaluacionRepository implements EvaluacionInterface
{

    public function GuardarEvaluacion($evaluacion_id, $userId, $nota)
    {
        evaluacionCompleta::updateOrCreate(
            ['id_u' => $userId, 'id_e' => $evaluacion_id],
            ['completado' => 'si', 'nota' => $nota]
        );

    }
    public function listarEvaluacion($id_e)
    {

        return Evaluacion::with('preguntas.opciones')->find($id_e);

    }
    public function GetEvaluacionesEstudiantes($id_pm)
    {
        // Encuentra el eval_paramodulo por su ID
        $evaluaciones = Evaluacion::where('id_pm', $id_pm)->get();

        $estudiantesEvaluaciones = [];
        foreach ($evaluaciones as $evaluacion) {
            // Obtiene las evaluaciones completas relacionadas a las evaluaciones
            $evaluaciones_c = evaluacionCompleta::where('id_e', $evaluacion->id)->get();

            // Itera sobre cada evaluación
            foreach ($evaluaciones_c as $evaluacion) {
                // Si el estudiante ya está en el array, añade la evaluación a su lista

                $encontrado = false;
                foreach ($estudiantesEvaluaciones as &$estudiante) {


                    if ($estudiante['user_id'] == $evaluacion->id_u) {
                        $estudiante['evaluaciones'][] = [
                            'id_e' => $evaluacion->id_e,
                            'nota' => $evaluacion->nota,
                        ];
                        $encontrado = true;
                        break;
                    }
                }

                // Si el estudiante no está en el array, crea una nueva entrada
                if (!$encontrado) {
                    $nombre_estudiante = User::find($evaluacion->id_u);
                    $estudiantesEvaluaciones[] = [
                        'user_id' => $evaluacion->id_u,
                        'estudiante' => $nombre_estudiante->name . ' ' . $nombre_estudiante->apepat . $nombre_estudiante->apemat,

                        'evaluaciones' => [
                            [
                                'id_e' => $evaluacion->id_e,
                                'nota' => $evaluacion->nota,
                            ]
                        ],
                    ];
                }
            }
        }


        return $estudiantesEvaluaciones;
    }

    public function GetEvaluacionesEstudiante($id_pm, $estudiante_id)
    {
        // Encuentra el eval_paramodulo por su ID
        $evaluacionesc = Evaluacion::where('id_pm', $id_pm)->get();

        $estudiantesEvaluaciones = [];
        foreach ($evaluacionesc as $evaluacionc) {
            // Obtiene las evaluaciones completas relacionadas a las evaluaciones

            $evaluaciones = evaluacionCompleta::where('id_e', $evaluacionc->id)
                ->where('id_u', $estudiante_id) // Filtra por el ID del estudiante
                ->get();
            // Itera sobre cada evaluación
            foreach ($evaluaciones as $evaluacion) {
                // Si el estudiante ya está en el array, añade la evaluación a su lista

                $encontrado = false;
                foreach ($estudiantesEvaluaciones as &$estudiante) {


                    if ($estudiante['user_id'] == $evaluacion->id_u) {
                        $estudiante['evaluaciones'][] = [
                            'id_e' => $evaluacion->id_e,
                            'evaluacion' => Evaluacion::find($evaluacion->id_e)->nombre,
                            'nota' => $evaluacion->nota,
                        ];
                        $encontrado = true;
                        break;
                    }
                }

                // Si el estudiante no está en el array, crea una nueva entrada
                if (!$encontrado) {
                    $nombre_estudiante = User::find($evaluacion->id_u);
                    $estudiantesEvaluaciones[] = [
                        'user_id' => $evaluacion->id_u,
                        'estudiante' => $nombre_estudiante->name . ' ' . $nombre_estudiante->apepat . $nombre_estudiante->apemat,

                        'evaluaciones' => [
                            [
                                'id_e' => $evaluacion->id_e,
                                'evaluacion' => Evaluacion::find($evaluacion->id_e)->nombre,
                                'nota' => $evaluacion->nota,
                            ]
                        ],
                    ];
                }
            }
        }
        return $estudiantesEvaluaciones;
    }
}