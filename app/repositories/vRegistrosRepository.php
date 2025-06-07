<?php

namespace App\Repositories;

use App\Models\VerificaRegistro;
use App\Interfaces\VerificaInterface;
class vRegistrosRepository implements VerificaInterface  {
 
    public function cambio ( $dato)
    {

        $registro = VerificaRegistro::findOrFail(1);
        $registro->activo = $dato;
        $registro->save();
        
    }
}