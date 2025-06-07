<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\Estudio;
use App\Models\Experiencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\NotificationInterface;
use App\Interfaces\UserInterface;



class ProfesorController extends Controller
{
    protected $userid;
    protected $NotificationRepository;

    protected $UserRepository;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(NotificationInterface $NotificationRepository, UserInterface $UserRepository)
    {
        $this->NotificationRepository = $NotificationRepository;
        $this->UserRepository = $UserRepository;
    }
    public function index()
    {
        $userid = Auth::id();
        $datosP = Profesor::where('id_u', $userid)->first();
        $expP = Experiencia::where('id_p', $userid)->get();
        $eduP = Estudio::where('id_p', $userid)->get();


        return view('Personal.index', compact('datosP', 'expP', 'eduP'));
    }
    public function indexS()
    {
        $userid = Auth::id();
        $datosP = Profesor::where('id_u', $userid)->first();
        $expP = Experiencia::where('id_p', $userid)->get();
        $eduP = Estudio::where('id_p', $userid)->get();


        return view('Personal.index', compact('datosP', 'expP', 'eduP'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function horarios()
    {
        $userid = Auth::id();
        $horariosF = $this->UserRepository->getHorariosProfesor($userid);

        return view('personal.horarios', compact('horariosF'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeProfesion(Request $request)
    {
        $userid = Auth::id();
        $profesor = Estudio::create([
            'id_p' => $userid,
            'institucion' => $request['institucion'],
            'carrera' => $request['carrera'],
            'semestre' => $request['semestre'],
            'concluido' => $request['concluido'],
        ]);
        return back()->with('status', 'se ha registrado exitosamente!');
    }
    public function storeExperiencia(Request $request)
    {
        $userid = Auth::id();
        $profesor = Experiencia::create([
            'id_p' => $userid,
            'lugar' => $request['lugar'],
            'actividad' => $request['actividad'],
            'duracion' => $request['duracion'],
        ]);
        return back()->with('status', 'se ha registrado exitosamente!');
    }
    public function storeMensaje(Request $request)
    {
        $userid = Auth::id();
        $profesor = Profesor::create([
            'id_u' => $userid,
            'mensaje' => $request['mensaje'],
            'cargo' => $request['cargo'],
        ]);
        return back()->with('status', 'se ha registrado exitosamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profesor  $profesor
     * @return \Illuminate\Http\Response
     */
    public function show(Profesor $profesor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profesor  $profesor
     * @return \Illuminate\Http\Response
     */
    public function edit(Profesor $profesor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profesor  $profesor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profesor $profesor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profesor  $profesor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profesor $profesor)
    {
        //
    }
}
