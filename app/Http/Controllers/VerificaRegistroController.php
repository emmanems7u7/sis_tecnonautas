<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Repositories\vRegistrosRepository;

class VerificaRegistroController extends Controller
{


    protected $VRegistrosRepository;

    public function __construct(vRegistrosRepository $verificaInterface)
    {
        $this->VRegistrosRepository = $verificaInterface;
    }


    public function storeEstudiante()
    {
        $this->VRegistrosRepository->cambio(1);
        return back()->with('status', 'Ahora podrán Regristrarse Estudiantes');
    }

    public function storeProfesor()
    {
        $this->VRegistrosRepository->cambio(2);
        return back()->with('status', 'Ahora podrán Regristrarse Profesores');
    }
    public function storeAdmin()
    {
        $this->VRegistrosRepository->cambio(3);
        return back()->with('status', 'Ahora podrán Regristrarse administradores');
    }



}
