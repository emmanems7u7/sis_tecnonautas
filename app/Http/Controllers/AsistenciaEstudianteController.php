<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaEstudiante;

use App\Models\User;
use App\Models\Estudiantes_asignacion_paramodulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class AsistenciaEstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function registrar_asistencia($id, Request $request)
    {
        // Validar los datos que llegan del cliente
        $request->validate([
            'asistencia' => 'required|string',
        ]);
        $fechaActual = Carbon::now()->format('Y-m-d');
        $userId = Auth::id();

        $asistencia = AsistenciaEstudiante::where('user_id', $userId)->where('fecha', $fechaActual)->where('id_pm', $id)->first();

        if ($asistencia) {

            if ($fechaActual == $asistencia->fecha) {

                // Actualizar la asistencia con los nuevos datos
                $asistencia->asistencia = $request->input('asistencia');
                $asistencia->save();

                return response()->json([
                    'success' => true,
                    'message' => 'La asistencia ha sido editada correctamente.',
                    'data' => $asistencia
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró la asistencia para editar.'
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id_pm)
    {
        $lista = AsistenciaEstudiante::where('id_pm', $id_pm)->get();
        $users = Estudiantes_asignacion_paramodulo::where('id_pm', $id_pm)->get()

            ->map(function ($user) {
                $usuario = User::find($user->id_u);
                $nombre_estudiante = $usuario->usuario_nombres . ' ' . $usuario->usuario_app . ' ' . $usuario->usuario_apm;
                return (object) [
                    'id' => $user->id_u,
                    'nombre_completo' => $nombre_estudiante,
                ];
            });

        return response()->json([
            'asistencias' => $lista,
            'usuarios' => $users
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AsistenciaEstudiante $asistenciaEstudiante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AsistenciaEstudiante $asistenciaEstudiante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AsistenciaEstudiante $asistenciaEstudiante)
    {
        //
    }
}
