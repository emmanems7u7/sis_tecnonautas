<?php
namespace App\Interfaces;

interface RespuestasInterface {
    public function list ( $evaluacion_id);
    public function listU ( $id,$evaluacion_id);
    public function storeRespuestas( $request);
    public function Nota($totalPreguntas,$preguntas);
    public function Nota2($id_u,$totalPreguntas,$preguntas);

}