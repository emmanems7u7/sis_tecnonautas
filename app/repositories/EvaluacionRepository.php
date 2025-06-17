<?php

namespace App\Repositories;

use App\Models\Evaluacion;
use App\Models\Tarea;

use App\Models\User;
use App\Models\evaluacionCompleta;
use App\Interfaces\EvaluacionInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Estudiantes_asignacion_paramodulo;

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

        $estudiantes = Estudiantes_asignacion_paramodulo::with('usuario') // <- Cargar relación con User si existe
            ->where('id_pm', $id_pm)
            ->get();

        $evaluaciones = Evaluacion::where('id_pm', $id_pm)->get();

        // Indexar todas las evaluaciones completas por id_e + id_u
        $evalCompletaRaw = evaluacionCompleta::whereIn('id_e', $evaluaciones->pluck('id'))
            ->get()
            ->groupBy('id_u');

        // Inicializar estructura base
        $estudiantesEvaluaciones = [];

        foreach ($estudiantes as $est) {
            $user = $est->usuario;

            $registro = [
                'user_id' => $est->id_u,
                'estudiante' => $user->usuario_nombres . ' ' . $user->usuario_app . ' ' . $user->usuario_apm,
                'evaluaciones' => []
            ];

            foreach ($evaluaciones as $evaluacion) {
                // Buscar si el estudiante tiene nota en esta evaluación
                $nota = optional(
                    $evalCompletaRaw->get($est->id_u)
                )->firstWhere('id_e', $evaluacion->id);

                $registro['evaluaciones'][] = [
                    'id_e' => $evaluacion->id,
                    'nota' => $nota ? $nota->nota : 0,
                ];
            }

            $estudiantesEvaluaciones[] = $registro;
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
                        'estudiante' => $nombre_estudiante->usuario_nombres . ' ' . $nombre_estudiante->usuario_app . $nombre_estudiante->usuario_apm,

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

    public function notasEstudiantes($estudiantesEvaluaciones, $estudiantesTareas, $id_p)
    {

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

        return $data;
    }
}