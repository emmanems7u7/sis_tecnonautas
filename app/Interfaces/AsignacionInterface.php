<?php
namespace App\Interfaces;

interface AsignacionInterface
{
    public function InscripcionPago($userId, $request);
    public function InscripcionGratuito($userId, $request);
    public function GetInscripcion($request);

    public function GetAsignaciones();
    public function GetAsignacion($id_a);
    public function GetAsignacionPagos();
    public function GetAsignacionGratuitos();
    public function GuardarAsignacion($request);
    public function RegistrarPago($asignacion_id, $userN);

}