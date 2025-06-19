<?php

namespace App\Repositories;


use App\Interfaces\PreguntasInterface;
use App\Models\Opcion;
use App\Models\Preguntas;

class PreguntasRepository implements PreguntasInterface  {

    public function list ( $evaluacion_id)
    {
        $preguntas=Preguntas::where('evaluacion_id', $evaluacion_id)->get();
        return $preguntas;
    } 
    public function storePreguntas ( $request)
    {
        $request->validate([
            'texto_pregunta' => 'required|string',
            'tipo_pregunta' => 'required|in:parrafo,opciones,casillas',
            'opciones' => 'required_if:tipo_pregunta,opciones,casillas|array',
            //'opciones.*' => 'string',
        ]);

        $pregunta = Preguntas::create([
            'texto' => $request->input('texto_pregunta'),
            'tipo' => $request->input('tipo_pregunta'),
            'evaluacion_id'=> $request['id_e'],

        ]);
     
        if ($request->input('tipo_pregunta') === 'opciones' || $request->input('tipo_pregunta') === 'casillas') {
            $opciones = $request->input('opciones');
        
            // Verifica tipo de pregunta
            if ($request->input('tipo_pregunta') === 'opciones') {
                $opcionCorrectaIndex = $request->input('opcion_correcta');
                $opcionCorrectaIndices = [$opcionCorrectaIndex];
            } elseif ($request->input('tipo_pregunta') === 'casillas') {
                $opcionCorrectaIndices = $request->input('opciones_correctas', []); 
            }
        
            foreach ($opciones as $indice => $opcionTexto) {
                $opcion = new Opcion([
                    'texto' => $opcionTexto,
                ]);
                $pregunta->opciones()->save($opcion);
        
                // Verifica opción correcta 
                if (in_array($indice, $opcionCorrectaIndices)) {
                    $opcion->correcta = true;
                } else {
                    $opcion->correcta = false;
                }
        
                $opcion->save(); // Guardar la opción con la propiedad "correcta" actualizada
            }
        }
        
    }
    public function getPreguntas($evaluacion_id)
    {
         
        return Preguntas::where('evaluacion_id',$evaluacion_id)->get();
    }
    public function CountPreguntas($evaluacion_id)
    {
       return Preguntas::where('evaluacion_id',$evaluacion_id)->count();
    }
}