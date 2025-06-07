<?php
namespace App\Interfaces;

interface ParalelosInterface
{
    public function ParaleloUpdate($request, $id);
    public function ParaleloDestroy($id);
    public function GetDatosParalelos($paralelos, $userRepository);
    public function GetDatosParalelosProfesor($paralelos, $userRepository, $profesorId);
    public function GetDatosMateriaModuloParalelos($paralelosModulos, $userRepository);

    public function GetParalelosDisponibles($paralelos, $id_m);
    public function getDatosParalelo($id_pm);

    public function getDatosParaleloI($paralelosModulo, $userRepository);
    public function GetDatosParalelosID($paralelosModulos);
}