<?php

namespace App\Http\Controllers;

use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\ExportExcel;
use App\Exports\ExportPDF;

use Maatwebsite\Excel\Facades\Excel;



use App\Models\Estudiantes_asignacion_paramodulo;

use App\Models\Celular;
use App\Models\Apoderado;
use App\Models\admpago;
use App\Models\Modulo;
use App\Models\Asignacion;
use App\Models\paralelo_modulo;
use App\Models\Paralelo;
use App\Interfaces\NotificationInterface;
use App\Models\asignacion_profesor;
use App\Models\asigModulo;
use DB;

use DateTime;
use InvalidArgumentException;
use App\Models\evaluacionCompleta;
use App\Models\Tarea;
use App\Models\Evaluacion;
use App\Models\horario;
use App\Models\AsistenciaEstudiante;
use App\Interfaces\TareasInterface;
use App\Interfaces\EvaluacionInterface;
use App\Models\ConfiguracionCredenciales;
use Illuminate\Support\Facades\Hash;

use App\Models\Profesor;
use App\Models\Experiencia;
use App\Models\Estudio;
use App\Models\EvalPorEstudiante;
use App\Models\tareas_estudiante;
use PhpParser\Node\Expr\Eval_;

class UserController extends Controller
{
    protected $userRepository;
    protected $NotificationRepository;
    protected $TareasRepository;
    protected $EvaluacionRepository;

    public function __construct(UserInterface $userInterface, NotificationInterface $NotificationRepository, TareasInterface $TareasRepository, EvaluacionInterface $EvaluacionRepository)
    {
        $this->userRepository = $userInterface;

        $this->NotificationRepository = $NotificationRepository;

        $this->TareasRepository = $TareasRepository;
        $this->EvaluacionRepository = $EvaluacionRepository;
    }

    public function index()
    {
        $users = User::paginate(5); // Trae todos los usuarios

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Usuarios', 'url' => route('users.index')],
        ];
        return view('usuarios.index', compact('users', 'breadcrumb'));
    }
    public function reestablecer_contraseña($id)
    {
        $user = User::find($id);
        $conf = ConfiguracionCredenciales::first();

        $user->password = Hash::make($conf->conf_defecto);
        $user->usuario_fecha_ultimo_password = null;
        $user->save();

        return redirect()->back()->with('status', 'Se reestablecio la contraseña de ' . $user->name . ' Correctamente');

    }



    // Mostrar el formulario para crear un nuevo usuario
    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Usuarios', 'url' => route('users.index')],
            ['name' => 'Crear', 'url' => route('users.create')],
        ];
        return view('usuarios.create', compact('breadcrumb'));
    }
    public function Perfil()
    {
        $user = Auth::user()->load('documentos');

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Perfil', 'url' => route('users.index')],
        ];

        $userid = Auth::id();
        $datosP = Profesor::where('id_u', $userid)->first();
        $expP = Experiencia::where('id_p', $userid)->get();
        $eduP = Estudio::where('id_p', $userid)->get();

        return view('usuarios.perfil', compact('datosP', 'expP', 'eduP', 'user', 'breadcrumb'));
    }

    // Almacenar un nuevo usuario
    public function store(Request $request)
    {
        session(['form_action' => 'store']);

        $user = $this->userRepository->CrearUsuario($request);

        $user->assignRole($request->input('role'));
        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('users.index')->with('success', 'Usuario registrado exitosamente!');
    }


    // Mostrar la información de un usuario
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Mostrar el formulario para editar un usuario
    public function edit($id)
    {
        $user = User::find($id);

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Usuarios', 'url' => route('users.index')],
            ['name' => 'Ecitar ' . $user->name, 'url' => route('users.index')],
        ];

        return view('usuarios.edit', compact('user', 'breadcrumb'));
    }

    // Actualizar un usuario
    public function update(request $request, $id, $perfil)
    {


        session(['form_action' => 'update']);
        session(['user_id' => $id]);

        $user = $this->userRepository->EditarUsuario($request, $id, $perfil);


        if ($perfil == 1) {
            return redirect()->back()->with('status', 'Datos personales actualizados exitosamente!');

        } else {
            if ($user->roles->isNotEmpty()) {
                $user->syncRoles([]); // Elimina todos los roles
            }
            // Asignar el nuevo rol
            $user->assignRole($request->input('role'));
            return redirect()->route('users.index')->with('status', 'Usuario actualizado exitosamente!');

        }

    }
    public function updateDatosPersonales(request $request, $id)
    {
        session(['form_action' => 'update']);
        session(['user_id' => $id]);

        $this->userRepository->EditarDatosPersonales($request, $id);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente!');
    }
    // Eliminar un usuario
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
    }

    public function exportExcel()
    {
        $export = new ExportExcel('usuarios.export_usuarios', ['users' => User::all(), 'export' => 'Usuarios'], 'usuarios');
        return Excel::download($export, $export->getFileName());
    }

    public function exportPDF()
    {
        $users = User::all();

        return ExportPDF::exportPdf('usuarios.export_usuarios', ['users' => $users, 'export' => 'Usuarios'], 'usuarios', false);
    }



    public function exportNotasExcel($id_a, $id_m, $id_p)
    {

        $user = Auth::user();

        $nombre = $user->usuario_nombres . ' ' . $user->usuario_app . ' ' . $user->usuario_apm;

        $hora = now();

        $asignacion = Asignacion::find($id_a);

        $modulo = Modulo::find($id_m);

        $total = Estudiantes_asignacion_paramodulo::where('id_pm', operator: $id_p)->count();


        $estudiantesEvaluaciones = $this->EvaluacionRepository->GetEvaluacionesEstudiantes($id_p);

        $estudiantesTareas = $this->TareasRepository->GetTareasEstudiantes($id_p);

        $data = $this->EvaluacionRepository->notasEstudiantes($estudiantesEvaluaciones, $estudiantesTareas, $id_p);


        $export = new ExportExcel('modulos.export_notas', [
            'data' => $data,
            'export' => 'Notas',
            'nombre' => $nombre,
            'hora' => $hora,
            'asignacion' => $asignacion,
            'modulo' => $modulo,
            'total' => $total


        ], 'notas');
        return Excel::download($export, $export->getFileName());
    }

    public function exportNotasPDF($id_a, $id_m, $id_p)
    {
        $user = Auth::user();

        $nombre = $user->usuario_nombres . ' ' . $user->usuario_app . ' ' . $user->usuario_apm;

        $hora = now();

        $asignacion = Asignacion::find($id_a);

        $modulo = Modulo::find($id_m);

        $total = Estudiantes_asignacion_paramodulo::where('id_pm', operator: $id_p)->count();


        $estudiantesEvaluaciones = $this->EvaluacionRepository->GetEvaluacionesEstudiantes($id_p);

        $estudiantesTareas = $this->TareasRepository->GetTareasEstudiantes($id_p);

        $data = $this->EvaluacionRepository->notasEstudiantes($estudiantesEvaluaciones, $estudiantesTareas, $id_p);

        return ExportPDF::exportPdf('modulos.export_notas', [
            'data' => $data,
            'export' => 'Notas',
            'nombre' => $nombre,
            'hora' => $hora,
            'asignacion' => $asignacion,
            'modulo' => $modulo,
            'total' => $total


        ], 'Notas', false, ['orientation' => 'L']);
    }


    public function Asignar($estudiante_id, $id_m, $id_p)
    {

        $estudiantesEvaluaciones = $this->EvaluacionRepository->GetEvaluacionesEstudiante($id_p, $estudiante_id);

        $estudiantesTareas = $this->TareasRepository->GetTareasEstudiante($id_p, $estudiante_id);


        if (!$estudiantesEvaluaciones) {
            $estudiantesEvaluaciones = [
                'evaluaciones' => [] // O cualquier valor predeterminado que desees
            ];
            // return response()->json(['error' => 'No se pudieron obtener las evaluaciones '], 500);
        }
        if (!$estudiantesTareas) {
            $estudiantesTareas = [
                'tareas' => [] // O cualquier valor predeterminado que desees
            ];
            //   return response()->json(['error' => 'No se pudieron obtener las  tareas.'], 500);
        }

        // Agregar las evaluaciones a las tareas (en caso de que ambas existan)

        $estudiantesTareas['evaluaciones'] = $estudiantesEvaluaciones[0]['evaluaciones'] ?? [];


        // Total de tareas y evaluaciones
        $totaltareas = Tarea::where('id_pm', $id_p)->count();
        $totalEvaluaciones = Evaluacion::where('id_pm', $id_p)->count();

        if ($totalEvaluaciones < 0) {
            return response()->json(['error' => 'Error al contar evaluaciones.'], 500);
        }
        if ($totaltareas < 0) {
            return response()->json(['error' => 'Error al contar tareas '], 500);
        }

        $totalProm = $totaltareas + $totalEvaluaciones;

        // Filtrar el estudiante específico en el array de estudiantes

        if ($estudiantesTareas['user_id'] == $estudiante_id) {
            // Inicializar sumas locales
            $sumaNotasTareas = 0;
            $sumaNotasEvaluaciones = 0;

            // Calcular suma de notas de tareas
            foreach ($estudiantesTareas['tareas'] as $tarea) {
                $sumaNotasTareas += $tarea['nota'];
            }

            // Calcular suma de notas de evaluaciones
            foreach ($estudiantesTareas['evaluaciones'] as $evaluacion) {
                $sumaNotasEvaluaciones += $evaluacion['nota'];
            }

            // Añadir promedios al estudiante
            $estudiante['nota_tareas'] = $sumaNotasTareas;
            $estudiante['nota_evaluaciones'] = $sumaNotasEvaluaciones;
            $estudiante['nota_final'] = round(($estudiante['nota_tareas'] + $estudiante['nota_evaluaciones']) / $totalProm, 1);


            $datosEstudiante = [
                'estudiante_id' => $estudiantesTareas['user_id'],
                'estudiante' => $estudiantesTareas['estudiante'],
                'Tareas' => $estudiantesTareas['tareas'],
                'evaluaciones' => $estudiantesTareas['evaluaciones'],
                'suma_tareas' => $estudiante['nota_tareas'],
                'suma_evaluaciones' => $estudiante['nota_evaluaciones'],
                'nota_final' => $estudiante['nota_final'],
            ];

            // Retornar la información solo para ese estudiante
            return response()->json($datosEstudiante);
        }
        return response()->json(['error' => 'Estudiante no encontrado.'], 404);



    }

    public function Generar_asistencia(Request $request)
    {

        $validated = $request->validate([
            'id_pm' => 'required|integer|exists:paralelo_modulos,id'
        ]);

        $id_pm = $validated['id_pm'];

        $paralelo_modulo = paralelo_modulo::find($id_pm);

        $fecha = $paralelo_modulo->mes;

        $diasSemanaRegistro = horario::where('id_mp', $id_pm)->first();

        // Asegurarte de que se encontró el registro
        if ($diasSemanaRegistro) {
            // Convertir la columna 'dias' en un array
            $diasSemana = explode(',', $diasSemanaRegistro->dias);
        } else {
            // Manejar el caso donde no se encuentra un registro
            $diasSemana = [];
        }


        // Crear un objeto DateTime a partir de la fecha proporcionada
        $fechaObj = new DateTime($fecha);

        // Obtener el primer día del mes
        $primerDia = $fechaObj->modify('first day of this month');

        // Clonar el objeto para no modificar el original
        $ultimoDia = clone $fechaObj;
        $ultimoDia->modify('last day of this month');

        // Crear un array con los días de la semana en español
        $diasDeLaSemana = [
            'Lunes' => 'Monday',
            'Martes' => 'Tuesday',
            'Miércoles' => 'Wednesday',
            'Jueves' => 'Thursday',
            'Viernes' => 'Friday',
            'Sábado' => 'Saturday',
            'Domingo' => 'Sunday'
        ];

        // Verificar que los días de la semana solicitados están en el formato correcto
        foreach ($diasSemana as $dia) {
            if (!array_key_exists($dia, $diasDeLaSemana)) {
                throw new InvalidArgumentException("Día de la semana inválido: $dia");
            }
        }

        // Obtener todos los días del mes que coinciden con los días de la semana especificados
        $diasDelMes = [];
        while ($primerDia <= $ultimoDia) {
            $diaNombre = $primerDia->format('l');
            $diaEspañol = array_search($diaNombre, $diasDeLaSemana);

            if (in_array($diaEspañol, $diasSemana)) {
                $diasDelMes[] = [
                    'fecha' => $primerDia->format('Y-m-d'),
                    'dia' => $diaEspañol
                ];
            }
            $primerDia->modify('+1 day');
        }


        $estudiantes = Estudiantes_asignacion_paramodulo::where('id_pm', $id_pm)->get();
        foreach ($estudiantes as $estudiante) {
            foreach ($diasDelMes as $dia) {

                $asistenciaExistente = AsistenciaEstudiante::where('user_id', $estudiante->id_u)
                    ->where('id_pm', $id_pm)
                    ->where('fecha', $dia['fecha'])
                    ->first();


                if (!$asistenciaExistente) {
                    AsistenciaEstudiante::create([
                        'user_id' => $estudiante->id_u,
                        'id_pm' => $id_pm,
                        'fecha' => $dia['fecha'],
                        'asistencia' => 'falta',
                    ]);
                }
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Asistencia registrada con éxito.',
        ]);


    }
    public function EstudiantesInactivos($id)
    {


        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Estudiantes Inactivos', 'url' => route('home')],
        ];

        $estudiantes = Estudiantes_asignacion_paramodulo::join('users', 'estudiantes_asignacion_paramodulos.id_u', '=', 'users.id')
            ->select('users.id', 'users.fotoperfil', 'users.usuario_nombres', 'users.usuario_app', 'users.usuario_apm', 'users.email', 'estudiantes_asignacion_paramodulos.activo')
            ->where('activo', 'inactivo')
            ->get();

        if ($estudiantes->isEmpty()) {
            return redirect()->back()->with('alert', ['type' => 'warning', 'message' => 'El Estudiante Aun no se registro en alguna materia']);
        }

        return view('estudiantes.inactivos', ['e' => $estudiantes, 'id_noti' => $id, 'breadcrumb' => $breadcrumb]);

    }
    public function cambiarestado($id)
    {
        Estudiantes_asignacion_paramodulo::where('id_u', $id)->update(['activo' => 'activo']);


        $estudiantes = Estudiantes_asignacion_paramodulo::join('users', 'estudiantes_asignacion_paramodulos.id_u', '=', 'users.id')
            ->select('users.id', 'users.fotoperfil', 'users.usuario_nombres', 'users.usuario_app', 'users.usuario_apm', 'users.email', 'estudiantes_asignacion_paramodulos.activo')
            ->where('activo', 'inactivo')
            ->get();

        return redirect()->back()->with('statuts', 'Se cambió el estado correctamente');

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function estudentShow()
    {
        $id = Auth::user()->id;
        $estudiante = User::where('id', $id)->first();

        $edad = (int) Carbon::createFromFormat('Y-m-d', $estudiante->fechanac)
            ->diffInYears(Carbon::now());


        $apoderados = [];
        $apoderadosF = Apoderado::where('id_u', $id)->get();
        if ($apoderadosF !== null) {

            foreach ($apoderadosF as $apoderado) {
                $celulares = Celular::where('id_u', $apoderado->id)->get();
                $celularesF = [];

                foreach ($celulares as $celular) {
                    $celularesF[] = [
                        'id' => $celular->id,
                        'celular' => $celular->celular,
                    ];
                }

                $apoderados[] = [
                    'id' => $id,
                    'nombre' => $apoderado->nombre,
                    'parentezco' => $apoderado->parentezco,
                    'apepat' => $apoderado->apepat,
                    'apemat' => $apoderado->apemat,
                    'fechanac' => $apoderado->fechanac,
                    'ci' => $apoderado->ci,
                    'nit' => $apoderado->nit,
                    'celular' => $celularesF,
                    'email' => $apoderado->email,
                ];
            }

        } else {
            $apoderados[] = null;
        }
        return view('estudiantes.show', compact('estudiante', 'apoderados', 'edad'));
    }

    public function ver($id_a, $id_p)
    {

        $paralelo_modulo = paralelo_modulo::find($id_p);


        $estudiantes = DB::table('estudiantes_asignacion_paramodulos as eap')
            ->join('users as u', 'u.id', '=', 'eap.id_u')
            ->leftJoin('estudiante_finalizados as ef', 'ef.user_id', '=', 'u.id') // LEFT JOIN para incluir estudiantes sin registros en 'estudiante_finalizados'
            ->select(
                'u.id',
                'u.fotoperfil',
                'u.usuario_nombres',
                'u.usuario_app',
                'u.usuario_apm',
                'u.email',
                DB::raw('COALESCE(ef.nota, 0
                ) as nota') // Si no hay nota, devuelve 0
            )
            ->where('eap.id_a', $id_a)
            ->where('eap.id_pm', $id_p)
            ->get();


        $data = [
            'finalizado' => $paralelo_modulo->activo,
            'dato' => 'Estudiantes inscritos',
            'estudiantes' => $estudiantes,
        ];

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function EstudiantesMatPagos($id)
    {
        $data = admpago::select('admpagos.id', 'users.name', 'modulos.nombreM', 'admpagos.pagado', 'admpagos.comprobante')
            ->join('users', 'admpagos.id_u', '=', 'users.id')
            ->join('asignacions', 'asignacions.id', '=', 'admpagos.id_a')
            ->join('modulos', 'modulos.id', '=', 'admpagos.id_m')
            ->where('asignacions.id', $id)
            ->get();
        return response()->json($data);
    }


    public function detalleEstudiante($id, $id_p, $id_a, $id_m)
    {



        $breadcrumb = [
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Modulos', 'url' => route('modulos.materia.show', ['id_a' => $id])],
            ['name' => 'Paralelos', 'url' => route('Paralelos.modulos.show', ['id_m' => $id_m, 'id_a' => $id])],
            ['name' => 'Administrar', 'url' => route('modulos.temas.admin', [$id_a, $id_m, $id_p])],
            ['name' => 'Detalle', 'url' => route('home')],

        ];

        $usuario = User::find($id);


        $estudiantesEvaluaciones = $this->EvaluacionRepository->GetAllEvaluacionesEstudiante($id_p, $usuario->id);


        $estudiantesTareas = $this->TareasRepository->GetAllTareasEstudiante($id_p, $usuario->id);

        $data = $this->EvaluacionRepository->notasEstudiante($estudiantesEvaluaciones, $estudiantesTareas, $id_p);

        return view('estudiantes.detalle', [
            'usuario' => $usuario,
            'data' => $data,
            'breadcrumb' => $breadcrumb,
            'id_p' => $id_p,
            'id_a' => $id_a,
            'id_m' => $id_m,
        ]);
    }

    public function EstudianteReporte($id, $id_m, $id_p)
    {
        $usuario = User::find($id);
        $id_a = asigModulo::where('id_m', $id_m)->value('id_a');
        $materia = Asignacion::find($id_a)->nombre;

        $nombreMod = Modulo::where('id', $id_m)->select('nombreM')->first();
        $id_pp = paralelo_modulo::where('id', $id_p)->select('id_p')->first();
        $paralelo = Paralelo::find($id_pp)->first();


        $estudiantesEvaluaciones = $this->EvaluacionRepository->GetAllEvaluacionesEstudiante($id_p, $usuario->id);

        $estudiantesTareas = $this->TareasRepository->GetAllTareasEstudiante($id_p, $usuario->id);


        foreach ($estudiantesEvaluaciones['evaluaciones'] as &$ev_data) {
            $eval_c = Evaluacion::find($ev_data['id_e']);

            $ev_data['nombre'] = $eval_c->nombre;
            $ev_data['detalle'] = $eval_c->detalle;
            $ev_data['creado'] = $eval_c->creado;
            $ev_data['limite'] = $eval_c->limite;


            $entregado = evaluacionCompleta::where('id_u', $usuario->id)->where('id_e', $eval_c->id)->first();

            if ($entregado != null) {
                $ev_data['entregado'] = $entregado->created_at;
            } else {
                $ev_data['entregado'] = 'No Entregado';
            }


        }

        foreach ($estudiantesTareas['tareas'] as &$tarea_data) {
            $tarea_c = Tarea::find($tarea_data['tareas_id']);

            $tarea_data['nombre'] = $tarea_c->nombre;
            $tarea_data['detalle'] = $tarea_c->detalle;
            $tarea_data['limite'] = $tarea_c->limite;

            if ($tarea_data['nota'] != 0) {
                $tarea = tareas_estudiante::where('user_id', $usuario->id)
                    ->where('tareas_id', $tarea_c->id)
                    ->first();

                $tarea_data['entregado'] = $tarea?->created_at;
            } else {
                $tarea_data['entregado'] = 'No Entregado / No Revisado';
            }
        }


        $evaluaciones = evaluacionCompleta::join('evaluacions as e', 'evaluacion_completas.id_e', '=', 'e.id')
            ->select(
                'e.id',
                'e.nombre',
                'e.detalle',
                'e.creado',
                'e.limite',
                'evaluacion_completas.completado',
                'evaluacion_completas.nota'
            )
            ->where('evaluacion_completas.id_u', $id)
            ->get();


        $sumaNotasEvs = 0;

        foreach ($evaluaciones as $evaluacion) {
            $evaluacion->creado = Carbon::parse($evaluacion->creado)->translatedFormat('j \d\e F \d\e Y \a \l\a\s h:i A');
            $evaluacion->limite = Carbon::parse($evaluacion->limite)->translatedFormat('j \d\e F \d\e Y \a \l\a\s h:i A');

            if (!is_null($evaluacion->nota)) {
                $sumaNotasEvs += $evaluacion->nota;
            }
        }

        $sumaNotasTareas = 0;


        $tareasEstudiantes = DB::table('tareas_estudiantes as te')
            ->join('tareas as t', 'te.tareas_id', '=', 't.id')
            ->select('t.nombre', 't.detalle', 't.limite', 'te.nota', 'te.created_at as entregado')
            ->where('te.user_id', $id)
            ->get();

        foreach ($tareasEstudiantes as $tarea) {
            $tarea->limite = Carbon::parse($tarea->limite)->translatedFormat('j \d\e F \d\e Y \a \l\a\s H:i');
            $tarea->entregado = Carbon::parse($tarea->entregado)->translatedFormat('j \d\e F \d\e Y \a \l\a\s H:i');
            if (!is_null($tarea->nota)) {
                $sumaNotasTareas += $tarea->nota;
            }
        }


        $cantidadEvs = $evaluaciones->count();
        $cantidadTareas = $tareasEstudiantes->count();

        $total = $cantidadEvs + $cantidadTareas;


        $sumTotal = $sumaNotasTareas + $sumaNotasEvs;

        if ($total > 0) {
            $nota = $sumTotal / $total;
        } else {
            $nota = 0;
        }

        $profesor = asignacion_profesor::join('users as u', 'asignacion_profesor.id_u', '=', 'u.id')
            ->select('u.usuario_nombres', 'u.usuario_app', 'u.usuario_apm')
            ->where('id_pm', $id_p)
            ->first();



        // dd($estudiantesEvaluaciones, $estudiantesTareas);


        $data = [
            'usuario' => $usuario,
            'nombreMod' => $nombreMod,
            'paralelo' => $paralelo,

            'profesor' => $profesor,
            'materia' => $materia,

            'nota' => $nota,

            'evaluacionesEstudiante' => $estudiantesEvaluaciones['evaluaciones'],
            'tareasEstudiantes' => $estudiantesTareas['tareas']


        ];

        return response()->json($data);
    }

    public function guardarConfiguracion(Request $request)
    {
        $user = auth()->user();
        $settings = $user->settings ?? [];

        $settings[$request->clave] = $request->valor;

        $user->settings = $settings;
        $user->save();

        return response()->json(['ok' => true]);
    }


}
