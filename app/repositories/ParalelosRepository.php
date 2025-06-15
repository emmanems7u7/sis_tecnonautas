<?php

namespace App\Repositories;

use App\Interfaces\ParalelosInterface;
use App\Models\asigModulo;
use App\Models\Asignacion;
use App\Models\paralelo_modulo;
use App\Models\User;
use App\Models\Paralelo;
use App\Models\horario;
use App\Models\Asignacion_profesor;
use App\Models\Modulo;

class ParalelosRepository implements ParalelosInterface
{


    public function ParaleloUpdate($request, $id)
    {

    }
    public function ParaleloDestroy($id)
    {

    }

    public function GetDatosMateriaModuloParalelos($paralelosModulos, $userRepository)
    {

        //  dd($paralelosModulos);
        if (!$paralelosModulos->isEmpty()) {
            foreach ($paralelosModulos as $paraleloModulo) {
                $asig_modulo = asigModulo::where('id_m', $paraleloModulo->id_m)->first();


                if ($asig_modulo->id_a) {
                    $paralelodatos = $this->getDatosParalelo($paraleloModulo->id);

                    $paraleloArr = [
                        'paralelo' => $paralelodatos->nombre,
                        'modulo' => Modulo::find($asig_modulo->id_m)->nombreM,
                        'cupo' => $paralelodatos->cupo,
                        'activo' => $paraleloModulo->activo,
                        'inscritos' => $paralelodatos->inscritos,
                        'id_p' => $paraleloModulo->id
                    ];
                }
                //  horarios asociados al paralelo
                $horarios = horario::where('id_mp', $paraleloModulo->id)->get();

                // arreglo de horarios
                foreach ($horarios as $horario) {
                    $paraleloArr['horarios'][$horario->dias] = [
                        'idh' => $horario->id,
                        'hora_inicio' => substr($horario->inicio, 0, 5),
                        'hora_fin' => substr($horario->fin, 0, 5),
                    ];
                }
                //  profesor asociado al paralelo
                $profesor = $userRepository->GetProfesorParalelo($paraleloModulo->id);

                // Asignar profesor al arreglo
                if ($profesor) {
                    $paraleloArr['profesor'] = $profesor->usuario_nombres . " " . $profesor->usuario_app . " " . $profesor->usuario_apm;

                }




                $datosPorAsignacion[Asignacion::find($asig_modulo->id_a)->nombre][

                ] = $paraleloArr;
            }
        } else {
            $datosPorAsignacion = $paralelosModulos;
        }

        return $datosPorAsignacion;
    }
    public function GetDatosParalelos($paralelosModulos, $userRepository)
    {

        //  dd($paralelosModulos);
        if (!$paralelosModulos->isEmpty()) {
            foreach ($paralelosModulos as $paraleloModulo) {

                //arreglo para el paralelo
                $paraleloArr = [
                    'activo' => null,
                    'horarios' => [],
                    'profesor' => null,
                    'inscritos' => null,
                    'cupo' => null,
                    'id_p' => null,
                ];

                //  horarios asociados al paralelo
                $horarios = horario::where('id_mp', $paraleloModulo->id)->get();

                // arreglo de horarios
                foreach ($horarios as $horario) {
                    $paraleloArr['horarios'][$horario->dias] = [
                        'idh' => $horario->id,
                        'hora_inicio' => substr($horario->inicio, 0, 5),
                        'hora_fin' => substr($horario->fin, 0, 5),
                    ];
                }
                //  profesor asociado al paralelo
                $profesor = $userRepository->GetProfesorParalelo($paraleloModulo->id);

                // Asignar profesor al arreglo
                if ($profesor) {
                    $paraleloArr['profesor'] = $profesor->usuario_nombres . " " . $profesor->usuario_app . " " . $profesor->usuario_apm;

                }
                $paralelodatos = $this->getDatosParalelo($paraleloModulo->id);
                $paraleloArr['cupo'] = $paralelodatos->cupo;
                $paraleloArr['activo'] = $paraleloModulo->activo;
                $paraleloArr['inscritos'] = $paralelodatos->inscritos;
                $paraleloArr['id_p'] = $paraleloModulo->id;

                $datosParalelos[$paralelodatos->nombre] = $paraleloArr;
            }
        } else {
            $datosParalelos = $paralelosModulos;
        }

        return $datosParalelos;
    }
    public function GetDatosParalelosProfesor($paralelosModulos, $userRepository, $profesorId)
    {


        $datosParalelos = [];

        if (!$paralelosModulos->isEmpty()) {
            foreach ($paralelosModulos as $paraleloModulo) {
                // Verificar si el paralelo está asociado al profesor
                $prof = Asignacion_profesor::where('id_u', $profesorId)->where('id_pm', $paraleloModulo->id)->first();
                if ($prof) {
                    //arreglo para el paralelo
                    $paraleloArr = [
                        'activo' => null,
                        'horarios' => [],
                        'profesor' => null,
                        'inscritos' => null,
                        'cupo' => null,
                        'id_p' => null,
                    ];

                    //  horarios asociados al paralelo
                    $horarios = horario::where('id_mp', $paraleloModulo->id)->get();

                    // arreglo de horarios
                    foreach ($horarios as $horario) {
                        $paraleloArr['horarios'][$horario->dias] = [
                            'idh' => $horario->id,
                            'hora_inicio' => substr($horario->inicio, 0, 5),
                            'hora_fin' => substr($horario->fin, 0, 5),
                        ];
                    }

                    //  profesor asociado al paralelo
                    $profesor = $userRepository->GetProfesorParalelo($paraleloModulo->id);

                    // Asignar profesor al arreglo
                    if ($profesor) {
                        $paraleloArr['profesor'] = $profesor->usuario_nombres . " " . $profesor->usuario_app . " " . $profesor->usuario_apm;
                    }

                    $paralelodatos = $this->getDatosParalelo($paraleloModulo->id);
                    $paraleloArr['cupo'] = $paralelodatos->cupo;
                    $paraleloArr['activo'] = $paraleloModulo->activo;
                    $paraleloArr['inscritos'] = $paralelodatos->inscritos;
                    $paraleloArr['id_p'] = $paraleloModulo->id;
                    $datosParalelos[$paralelodatos->nombre] = $paraleloArr;
                }
            }
        } else {
            $datosParalelos = $paralelosModulos;
        }

        return $datosParalelos;
    }
    public function GetDatosParalelosID($paralelosModulos)
    {


        if (!$paralelosModulos->isEmpty()) {
            foreach ($paralelosModulos as $paraleloModulo) {
                $paraleloArr = [

                    'id_p' => null,
                ];
                $CupoParalelo = Paralelo::Find($paraleloModulo->id_p);
                if ($paraleloModulo->inscritos < $CupoParalelo->cupo) {
                    $paralelodatos = $this->getDatosParalelo($paraleloModulo->id);
                    $paraleloArr['id_p'] = $paraleloModulo->id;
                    $datosParalelos[$paralelodatos->nombre] = $paraleloArr;
                }

            }
        } else {
            $datosParalelos = $paralelosModulos;
        }

        return $datosParalelos;
    }

    public function GetDatosParaleloI($paralelosModulos, $userRepository)
    {

        if (!$paralelosModulos->isEmpty()) {
            $paraleloModulo = $paralelosModulos->first();

            $paraleloArr = [
                'horarios' => [],
                'profesor' => null,
                'inscritos' => null,
                'cupo' => null,
                'id_p' => null,
            ];


            $horarios = horario::where('id_mp', $paraleloModulo->id)->get();


            foreach ($horarios as $horario) {
                $paraleloArr['horarios'][$horario->dias] = [
                    'idh' => $horario->id,
                    'hora_inicio' => substr($horario->inicio, 0, 5),
                    'hora_fin' => substr($horario->fin, 0, 5),
                ];
            }

            $profesor = $userRepository->GetProfesorParalelo($paraleloModulo->id);
            if ($profesor) {
                $paraleloArr['profesor'] = $profesor->usuario_nombres . " " . $profesor->usuario_app . " " . $profesor->usuario_apm;
            }


            $paralelodatos = $this->getDatosParalelo($paraleloModulo->id);
            $paraleloArr['cupo'] = $paralelodatos->cupo;
            $paraleloArr['inscritos'] = $paralelodatos->inscritos;
            $paraleloArr['id_p'] = $paraleloModulo->id;

            return $paraleloArr;
        } else {
            // Si no hay paralelos, retornar un arreglo vacío o null según sea necesario
            return null;
        }
    }

    public function GetParalelosDisponibles($paralelos, $id_m)
    {
        $paradisp = [];
        if ($paralelos !== null) {
            foreach ($paralelos as $paralelo) {
                $paramod = paralelo_modulo::where('id_p', $paralelo->id)->where('id_m', $id_m)->exists();
                if (!$paramod) {
                    $paradisp[] = $paralelo;
                }
            }

        }

        return $paradisp;
    }
    public function getDatosParalelo($id_pm)
    {
        return Paralelo::select('paralelos.nombre', 'paralelos.cupo', 'paralelo_modulos.inscritos')
            ->join('paralelo_modulos', 'paralelos.id', '=', 'paralelo_modulos.id_p')
            ->where('paralelo_modulos.id', $id_pm)
            ->first();
    }
}