<?php

namespace App\Repositories;

use App\Models\Tarea;
use App\Models\User;
use App\Models\evaluacionCompleta;
use App\Interfaces\TareasInterface;
use Illuminate\Support\Facades\Auth;

class TareasRepository implements TareasInterface
{

    public function GuardarTarea($evaluacion_id, $userId, $nota)
    {


    }
    public function listarTareas($id_e)
    {



    }
    public function GetTareasEstudiantes($id_pm)
    {
        // Obtener todas las tareas con las tareas de los estudiantes asociadas
        $tareas = Tarea::with('tareasEstudiantes')->where('id_pm', $id_pm)->get();

        $estudiantesTareas = [];

        // Iterar sobre las tareas para obtener las tareas de los estudiantes
        foreach ($tareas as $tarea) {
            // Obtener las tareas de los estudiantes relacionadas con la tarea actual
            $tareasEstudiantes = $tarea->tareasEstudiantes;

            foreach ($tareasEstudiantes as $tareaEstudiante) {
                // Verificar si el estudiante ya está en el array $estudiantesTareas

                $encontrado = false;
                foreach ($estudiantesTareas as &$estudiante) {
                    if ($estudiante['user_id'] == $tareaEstudiante->user_id) {
                        // Añadir la tarea a su lista de tareas
                        $estudiante['tareas'][] = [
                            'tareas_id' => $tareaEstudiante->tareas_id,

                            'nota' => $tareaEstudiante->nota,
                        ];
                        $encontrado = true;
                        break;
                    }
                }

                // Si el estudiante no está en el array, crear una nueva entrada
                if (!$encontrado) {
                    $nombre_estudiante = User::find($tareaEstudiante->user_id);


                    $estudiantesTareas[] = [
                        'user_id' => $tareaEstudiante->user_id,
                        'estudiante' => $nombre_estudiante->usuario_nombres . ' ' . $nombre_estudiante->usuario_app . ' ' . $nombre_estudiante->usuario_apm,
                        'tareas' => [
                            [
                                'tareas_id' => $tareaEstudiante->tareas_id,
                                'nota' => $tareaEstudiante->nota,
                            ]
                        ],
                    ];
                }
            }
        }
        return $estudiantesTareas;
    }
    public function GetTareasEstudiante($id_pm, $estudiante_id)
    {
        // Obtener todas las tareas con las tareas del estudiante asociadas al id_pm y user_id
        $tareas = Tarea::with([
            'tareasEstudiantes' => function ($query) use ($estudiante_id) {
                $query->where('user_id', $estudiante_id);
            }
        ])->where('id_pm', $id_pm)->get();

        $estudianteTareas = [];

        // Iterar sobre las tareas para obtener las tareas del estudiante
        foreach ($tareas as $tarea) {
            // Obtener las tareas del estudiante relacionadas con la tarea actual
            $tareasEstudiantes = $tarea->tareasEstudiantes;

            foreach ($tareasEstudiantes as $tareaEstudiante) {
                // Verificar si el estudiante ya está en el array $estudianteTareas
                if (empty($estudianteTareas)) {
                    $nombre_estudiante = User::find($tareaEstudiante->user_id);
                    $estudianteTareas = [
                        'user_id' => $tareaEstudiante->user_id,
                        'estudiante' => $nombre_estudiante->usuario_nombres . ' ' . $nombre_estudiante->usuario_app . ' ' . $nombre_estudiante->usuario_apm,

                        'tareas' => []
                    ];
                }

                // Añadir la tarea a su lista de tareas
                $estudianteTareas['tareas'][] = [
                    'tareas_id' => $tareaEstudiante->tareas_id,
                    'nombre' => Tarea::find($tareaEstudiante->tareas_id)->nombre,
                    'nota' => $tareaEstudiante->nota,
                ];
            }
        }

        // Si no hay tareas, retorna un mensaje vacío
        if (empty($estudianteTareas['tareas'])) {

            return [
                'user_id' => $estudiante_id,
                'estudiante' => User::find($estudiante_id)->usuario_nombres . ' ' . User::find($estudiante_id)->usuario_app . ' ' . User::find($estudiante_id)->usuario_apm,
                'tareas' => []
            ];
        }

        return $estudianteTareas;
    }

}