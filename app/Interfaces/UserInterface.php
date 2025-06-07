<?php

namespace App\Interfaces;

interface UserInterface
{
    public function CrearUsuario($request);
    public function EditarUsuario($request, $id, $perfil);
    public function EditarDatosPersonales($request, $id);
    public function GetUsuario($id);
    public function GetUsuarios();

    public function getEstudiantes();
    public function getEstudiante($id);

    public function getProfesores();
    public function getProfesor($id);
    public function getProfesorParalelo($id_pm);
    public function getAdministradores();
    public function getAdministrador($id);

    public function getHorariosProfesor($userid);

}
