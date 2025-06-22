<?php

namespace App\Interfaces;

interface CorreoInterface
{
    public function EditarPlantillaCorreo($request, $email);

    public function EditarConfCorreo($request, $email);
    function getMails($email, $fecha_inicio, $fecha_fin);
    function getMailsByDate($fecha_inicio, $fecha_fin);
    function CorreoPagos($email_apoderado, $fecha_pago, $fecha_fin);

}
