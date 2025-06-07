<?php
namespace App\Interfaces;

interface TareasInterface
{
    public function GuardarTarea($evaluacion_id, $userId, $nota);
    public function listarTareas($id_t);
    public function GetTareasEstudiantes($id_pm);
    public function GetTareasEstudiante($id_pm, $estudiante_id);
}