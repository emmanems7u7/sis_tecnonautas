<?php

namespace App\Http\Controllers;

use App\Models\Estudiantes_asignacion_paramodulo;
use Illuminate\Http\Request;
use App\Models\User;
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
use Carbon\Carbon;
use DateTime;
use InvalidArgumentException;
use App\Models\evaluacionCompleta;
use App\Models\Tarea;
use App\Models\Evaluacion;
use App\Models\horario;
use App\Models\AsistenciaEstudiante;
use App\Interfaces\TareasInterface;
use App\Interfaces\EvaluacionInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $NotificationRepository;
    protected $TareasRepository;
    protected $EvaluacionRepository;
    public function __construct(NotificationInterface $NotificationRepository, TareasInterface $TareasRepository, EvaluacionInterface $EvaluacionRepository)
    {
        $this->NotificationRepository = $NotificationRepository;

        $this->TareasRepository = $TareasRepository;
        $this->EvaluacionRepository = $EvaluacionRepository;
    }

    public function index(Request $request, $tipo)
    {
        $busqueda = $request->input('buscar');

        if ($tipo == 1) {
            $texto = 'Estudiantes';
            $e = User::with('asignacionesEstudiante')->role('estudiante')
                ->when($busqueda, function ($query, $busqueda) {
                    $query->where(function ($q) use ($busqueda) {
                        $q->where('usuario_nombres', 'like', "%$busqueda%")
                            ->orWhere('usuario_app', 'like', "%$busqueda%")
                            ->orWhere('usuario_apm', 'like', "%$busqueda%")
                            ->orWhere('email', 'like', "%$busqueda%");
                    });
                })
                ->paginate(5);
        } else {
            $texto = 'Profesores';
            $e = User::role('profesor')
                ->when($busqueda, function ($query, $busqueda) {
                    $query->where(function ($q) use ($busqueda) {
                        $q->where('usuario_nombres', 'like', "%$busqueda%")
                            ->orWhere('usuario_app', 'like', "%$busqueda%")
                            ->orWhere('usuario_apm', 'like', "%$busqueda%")
                            ->orWhere('email', 'like', "%$busqueda%");
                    });
                })
                ->paginate(5);


        }

        foreach ($e as $dato) {
            foreach ($dato->asignacionesEstudiante as $asig) {
                $asig->materia = Asignacion::find($asig->id_a)->nombre;
                $asig->modulo = Modulo::find(asigModulo::where('id_a', $asig->id_a)->first()->id_m)->nombreM;
            }
        }



        return view('estudiantes.index', [
            'e' => $e,
            'tipo' => $tipo,
            'texto' => $texto
        ]);
    }
    public function reestablecer_contraseña($id)
    {
        $user = User::find($id);
        $user->password = Hash::make(substr($user->name, 0, 3) . $user->ci);
        $user->save();
        return redirect()->back()->with('status', 'Se reestablecio la contraseña de ' . $user->name . ' Correctamente');

    }
    public function perfil()
    {
        $user = Auth::user();

        return view('perfil.index', compact('user'));
    }

    public function update_perfil(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'apepat' => 'required|string|max:255',
            'apemat' => 'required|string|max:255',
            'fechanac' => 'nullable|date',
            'ci' => 'required|integer',
            'direccion' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe superar los 255 caracteres.',

            'apepat.required' => 'El apellido paterno es obligatorio.',
            'apepat.string' => 'El apellido paterno debe ser una cadena de texto.',
            'apepat.max' => 'El apellido paterno no debe superar los 255 caracteres.',

            'apemat.required' => 'El apellido materno es obligatorio.',
            'apemat.string' => 'El apellido materno debe ser una cadena de texto.',
            'apemat.max' => 'El apellido materno no debe superar los 255 caracteres.',

            'fechanac.date' => 'La fecha de nacimiento debe ser una fecha válida.',

            'ci.required' => 'El número de cédula es obligatorio.',
            'ci.integer' => 'El número de cédula debe ser un número entero.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.string' => 'La dirección debe ser una cadena de texto.',
            'direccion.max' => 'La dirección no debe superar los 255 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
        ]);

        $user = auth()->user();


        $user->name = $request->name;
        $user->apepat = $request->apepat;
        $user->apemat = $request->apemat;
        $user->fechanac = $request->fechanac;
        $user->ci = $request->ci;
        $user->direccion = $request->direccion;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('status', 'Perfil actualizado con éxito.');
    }
    public function updatePhoto(Request $request)
    {
        // Validación de la foto
        $request->validate([
            'fotoperfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // validación para imagen
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Si el usuario ya tiene una foto, eliminarla de storage
        if ($user->fotoperfil && Storage::exists('public/' . $user->fotoperfil)) {
            Storage::delete('public/' . $user->fotoperfil);  // Eliminar foto anterior
        }

        // Subir la nueva foto
        if ($request->hasFile('fotoperfil')) {
            // Guardar la nueva foto en el almacenamiento
            $path = $request->file('fotoperfil')->store('imagenes', 'public');  // Guardar en public/profile_photos
            $user->fotoperfil = '/' . $path;  // Guardar la ruta en la base de datos

            $user->save();  // Guardar el usuario con la nueva ruta de la foto
        }

        // Redirigir al perfil con un mensaje de éxito
        return redirect()->back()->with('success', 'Foto de perfil actualizada correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Debes ingresar tu contraseña actual.',

            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'La contraseña actual es incorrecta.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('status', 'La contraseña se actualizó correctamente.');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        $estudiantes = Estudiantes_asignacion_paramodulo::join('users', 'estudiantes_asignacion_paramodulos.id_u', '=', 'users.id')
            ->select('users.id', 'users.fotoperfil', 'users.name', 'users.apepat', 'users.apemat', 'users.email', 'estudiantes_asignacion_paramodulos.activo')
            ->where('activo', 'inactivo')
            ->get();

        if ($estudiantes->isEmpty()) {
            return redirect()->back()->with('alert', ['type' => 'warning', 'message' => 'El Estudiante Aun no se registro en alguna materia']);
        }

        return view('estudiantes.inactivos', ['e' => $estudiantes, 'id_noti' => $id]);

    }
    public function cambiarestado($id, $id_noti)
    {
        Estudiantes_asignacion_paramodulo::where('id_u', $id)->update(['activo' => 'activo']);


        $this->NotificationRepository->markAsRead($id_noti);

        $estudiantes = Estudiantes_asignacion_paramodulo::join('users', 'estudiantes_asignacion_paramodulos.id_u', '=', 'users.id')
            ->select('users.id', 'users.fotoperfil', 'users.name', 'users.apepat', 'users.apemat', 'users.email', 'estudiantes_asignacion_paramodulos.activo')
            ->where('activo', 'inactivo')
            ->get();

        //return view('estudiantes.inactivos',['e' =>$estudiantes,'id_noti' =>$id_noti]);
        return redirect()->back();

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function estudentShow($id)
    {
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
                'u.name',
                'u.apepat',
                'u.apemat',
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
        $data = admPago::select('admpagos.id', 'users.name', 'modulos.nombreM', 'admpagos.pagado', 'admpagos.comprobante')
            ->join('users', 'admpagos.id_u', '=', 'users.id')
            ->join('asignacions', 'asignacions.id', '=', 'admpagos.id_a')
            ->join('modulos', 'modulos.id', '=', 'admpagos.id_m')
            ->where('asignacions.id', $id)
            ->get();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function detalleEstudiante($id, $id_m)
    {
        $usuario = User::find($id);
        $usuarioDetalle = DB::table('evaluacion_completas as ec')

            ->join('evaluacions as e', 'ec.id_e', '=', 'e.id')
            ->select('e.nombre', 'ec.completado', 'ec.nota', 'e.detalle', 'e.creado', 'e.limite', 'ec.id_u', 'ec.id_e')
            ->where('ec.id_u', $id)
            ->where('e.id_pm', $id_m)
            ->get();

        $tareasEstudiantes = DB::table('tareas_estudiantes as te')
            ->join('tareas as t', 'te.tareas_id', '=', 't.id')
            ->select('t.id', 't.nombre', 't.detalle', 't.limite', 'te.nota', 'te.created_at as entregado')
            ->where('te.user_id', $id)
            ->get();

        return view('estudiantes.detalle', [
            'usuario' => $usuario,
            'detalles' => $usuarioDetalle,
            'tareasEstudiantes' => $tareasEstudiantes
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

        $nota = $sumTotal / $total;

        $profesor = asignacion_profesor::join('users as u', 'asignacion_profesor.id_u', '=', 'u.id')
            ->select('u.name', 'u.apepat', 'u.apemat')
            ->where('id_pm', $id_p)
            ->first();


        $data = [
            'usuario' => $usuario,
            'nombreMod' => $nombreMod,
            'paralelo' => $paralelo,
            'evaluaciones' => $evaluaciones,
            'profesor' => $profesor,
            'materia' => $materia,
            'tareasEstudiantes' => $tareasEstudiantes,
            'nota' => $nota

        ];

        return response()->json($data);
    }

}
