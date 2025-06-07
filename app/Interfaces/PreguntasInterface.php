<?php
namespace App\Interfaces;

interface PreguntasInterface {
    public function list ( $evaluacion_id);
    public function storePreguntas ( $request);
   public function getPreguntas($evaluacion_id);

   
   public function CountPreguntas($evaluacion_id);

}