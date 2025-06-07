<?php

namespace App\Repositories;

use App\Interfaces\HorariosInterface;
use App\Models\horario;
class HorariosRepository implements HorariosInterface
{
    public function getHorarios ()
    {

    }
    public function getHorario ($id)
    {
        return horario::where('id_mp','=', $id)->get();
    }
    public function agregaHorario($horarios)
    {
        foreach($horarios as $horario)
        {
            $horariosD[]=
            [
                'id'  => $horario->id,
                'dia'=> $horario->dias,
                'horaInicio'=> $horario->inicio,
                'horaFin'=> $horario->fin,
            ];
        }
        return $horariosD;
    }
}