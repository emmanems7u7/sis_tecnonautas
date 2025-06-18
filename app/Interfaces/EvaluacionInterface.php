<?php
namespace App\Interfaces;

interface EvaluacionInterface
{
    public function GuardarEvaluacion($evaluacion_id, $userId, $nota);
    public function listarEvaluacion($id_e);
    public function GetEvaluacionesEstudiantes($id_pm);
    public function GetEvaluacionesEstudiante($id_pm, $estudiante_id);
    public function GetAllEvaluacionesEstudiante($id_pm, $id_u);
    public function notasEstudiantes($estudiantesEvaluaciones, $estudiantesTareas, $id_p);
    public function notasEstudiante($evaluacion, $tarea, $id_p);
}