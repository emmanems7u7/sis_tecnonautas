<?php
namespace App\Interfaces;

interface HorariosInterface {
    public function getHorarios ();
    public function getHorario($id);
   
    public function agregaHorario($horarios);
   

}