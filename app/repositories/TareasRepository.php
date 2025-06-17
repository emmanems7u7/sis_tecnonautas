<?php

namespace App\Repositories;

use App\Models\Tarea;
use App\Models\User;
use App\Models\evaluacionCompleta;
use App\Interfaces\TareasInterface;
use App\Models\Estudiantes_asignacion_paramodulo;
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

        $estudiantes = Estudiantes_asignacion_paramodulo::where('id_pm', $id_pm)->get();
        $tareas = Tarea::with('tareasEstudiantes')->where('id_pm', $id_pm)->get();

        $estudiantesTareas = [];

        // Paso 1: Armar la base de todos los estudiantes
        foreach ($estudiantes as $est) {
            $user = $est->usuario; // Asumiendo relación 'usuario' con User
            $estudiantesTareas[$est->id_u] = [
                'user_id' => $est->id_u,
                'estudiante' => $user->usuario_nombres . ' ' . $user->usuario_app . ' ' . $user->usuario_apm,
                'tareas' => [] // Se llenará después
            ];
        }

        // Paso 2: Para cada tarea, asignar nota si entregó o 'No entregado'
        foreach ($tareas as $tarea) {
            foreach ($estudiantesTareas as $user_id => &$estudiante) {
                // Buscar si este estudiante entregó esta tarea
                $entrega = $tarea->tareasEstudiantes->firstWhere('user_id', $user_id);

                $estudiante['tareas'][] = [
                    'tareas_id' => $tarea->id,
                    'nota' => $entrega ? $entrega->nota : 0,
                ];
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