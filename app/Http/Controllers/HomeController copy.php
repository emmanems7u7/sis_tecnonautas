<?php

namespace App\Http\Controllers;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Collection;
use App\Models\asigModulo;
use App\Models\Modulo;
use App\Models\horario;
use App\Models\paralelo_modulo;
use App\Models\Estudiantes_asignacion_paramodulo;
use App\Models\asignacion_profesor;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Interfaces\ParalelosInterface;
use App\Interfaces\UserInterface;







class HomeController extends Controller
{

    protected $ParaleloRepository;
    protected $UserRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ParalelosInterface $ParaleloRepository, UserInterface $UserRepository)
    {
        $this->ParaleloRepository = $ParaleloRepository;
        $this->UserRepository = $UserRepository;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $d = Carbon::now()->format('l');

        $id_pm = null;
        $diaT = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo',
        ];

        $dia = $diaT[$d];

        //cuenta estudiantes
        $rolE = Role::where('name', 'estudiante')->first();
        $students = $rolE->users()->count();
        //cuenta profesores
        $rolP = Role::where('name', 'profesor')->first();
        $profesor = $rolP->users()->count();
        //cuenta asignaciones
        $Casig = DB::table('asignacions')->count();

        $user = auth()->user();
        $userid = Auth::id();
        $horariosF = [];
        $verificaInscripcion = Estudiantes_asignacion_paramodulo::where('id_u', $userid)->get();

        $datosParalelos = $this->ParaleloRepository->GetDatosMateriaModuloParalelos(paralelo_modulo::all(), $this->UserRepository);


        if ($user->hasRole('estudiante')) {
            if ($verificaInscripcion->isEmpty()) {

                $asignacionesPago = Asignacion::where('tipo', 'pago')->get();
                $asignacionesGratuitos = Asignacion::where('tipo', 'gratuito')->get();

                return view(
                    'inscripciones.show',
                    [
                        'gratuitos' => $asignacionesGratuitos,
                        'pagos' => $asignacionesPago,
                        'datosParalelos' => null
                    ]
                );
            } else {
                $horariosF = [];

                foreach ($verificaInscripcion as $dato) {
                    $paralelo_modulo = paralelo_modulo::find($dato->id_pm);
                    if ($paralelo_modulo->activo == 1) {
                        $materia = Asignacion::find(id: $dato->id_a);
                        $modulo = Modulo::find($paralelo_modulo->id_m);
                        $horarios = horario::where('id_mp', $dato->id_pm)->get();
                        foreach ($horarios as $horario) {
                            $horario->inicio = Carbon::parse($horario->inicio)->format('H:i');
                            $horario->fin = Carbon::parse($horario->fin)->format('H:i');

                        }

                        $id_pm = $dato->id_pm;

                        $horariosF[] = [
                            'materia' => $materia->nombre . ' (' . $modulo->nombreM . ')',
                            'id_pm' => $paralelo_modulo->id,
                            'horarios' => $horarios,

                        ];
                    } else {
                        $id_pm = $dato->id_pm;
                    }


                }

                return view('home', [
                    'students' => $students,
                    'id_pm' => $id_pm,
                    'profesor' => $profesor,
                    'Casig' => $Casig,
                    'horariosF' => $horariosF,
                    'dia' => $dia,
                    'datosParalelos' => null
                ]);


            }

        } else {
            if ($user->hasRole('profesor')) {
                $horariosF = $this->UserRepository->getHorariosProfesor($userid);
                return view('home', [
                    'students' => $students,
                    'profesor' => $profesor,
                    'Casig' => $Casig,
                    'horariosF' => $horariosF,
                    'dia' => $dia,
                    'datosParalelos' => null
                ]);

            } else {

                return view('home', [
                    'students' => $students,
                    'profesor' => $profesor,
                    'Casig' => $Casig,
                    'horariosF' => $horariosF,
                    'dia' => $dia,

                    'datosParalelos' => $datosParalelos
                ]);
            }

        }








        //Extraer solo la fecha
//$fechaExtraida = Carbon::parse($notificacion[0]->created_at)->format('Y-m-d');


    }
}
