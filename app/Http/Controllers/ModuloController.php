<?php

namespace App\Http\Controllers;

use App\Models\EstudianteFinalizado;
use App\Models\Modulo;
use App\Models\Objetivo;
use App\Models\asigModulo;
use App\Models\User;
use App\Models\paralelo_modulo;
use App\Models\Asignacion;
use App\Models\Estudiantes_asignacion_paramodulo;
use Illuminate\Http\Request;
use App\Notifications\ModuloNoexiste;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Traits\Base64ToFile;
use App\Interfaces\AsignacionInterface;
use App\Interfaces\NotificationInterface;
use App\Mail\CorreoFinaliza;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Crypt;
use App\Mail\MiMailable;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;

use App\Notifications\RealizarPago;

use TCPDF;
class ModuloController extends Controller
{
    public $var;



    protected $NotificationRepository;


    protected $AsignacionRepository;
    public function __construct(AsignacionInterface $AsignacionRepository, NotificationInterface $NotificationRepository)
    {
        $this->AsignacionRepository = $AsignacionRepository;
        $this->NotificationRepository = $NotificationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_a)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Modulos', 'url' => route('asignacion.index')],

        ];

        $user = auth()->user();
        $dato = Estudiantes_asignacion_paramodulo::where('id_u', $user->id)->get();
        $modulosOriginales = $this->extraerModulos($id_a);

        $modulos = collect();

        $paralelo_modulos = paralelo_modulo::whereIn('id', collect($dato)->pluck('id_pm'))->get();

        $paramodulo = "";
        foreach ($modulosOriginales as $modulo) {
            $habilitado = 0;

            foreach ($dato as $paramod) {
                $paralelo_modulo = $paralelo_modulos->firstWhere('id', $paramod->id_pm);

                if ($paralelo_modulo && $paralelo_modulo->id_m == $modulo->id) {
                    $habilitado = 1;
                    $paramodulo = $paramod->id_pm;
                    break; // No es necesario seguir buscando si el módulo ya está habilitado
                }
            }
            if ($user->hasRole('admin') || $user->hasRole('profesor') || $user->hasRole('Demo')) {
                $habilitado = 1;
            }
            // Agregar el módulo procesado a la colección
            $modulos->push((object) [
                'id' => $modulo->id,
                'nombreM' => $modulo->nombreM,
                'habilitado' => $habilitado,
                'Descripcion' => $modulo->Descripcion,
                'Duracion' => $modulo->Duracion,
                'imagen' => $modulo->imagen,

                'ultimo_modulo' => $modulo->ultimo_modulo,
                'paramodulo' => $paramodulo,
            ]);
        }
        $e = $this->AsignacionRepository->GetAsignacion($id_a);

        $objetivos = Objetivo::where('id_a', $id_a)->get();

        $id_pm = Estudiantes_asignacion_paramodulo::where('id_a', $id_a)->where('id_u', $user->id)->value('id_pm');


        return view('modulos.show', compact('breadcrumb', 'modulos', 'id_a', 'id_pm', 'e', 'objetivos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $idcurso = $request->curso;

        return view('modulos.create', [
            'idcurso' => $idcurso,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'duracion' => 'required|string|max:500',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
            'cursoid' => 'required|integer|exists:asignacions,id',
        ]);

        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            $file = $request->file('imagen');
            $filename = uniqid() . $file->getClientOriginalExtension();
            $file->move(public_path('imagenes'), $filename);
            $photoPath = 'imagenes/' . $filename;
        }




        DB::transaction(function () use ($request, $photoPath) {
            $esUltimoModulo = $request->has('ultimo_modulo') ? true : false;
            $asig = Modulo::create([
                'nombreM' => $request['nombre'],
                'Descripcion' => $request['descripcion'],
                'Duracion' => $request['duracion'],

                'imagen' => (!empty($request['imagen'])) ? $photoPath : null,

                'ultimo_modulo' => $esUltimoModulo,


            ]);

            $asigmod = asigModulo::create([
                'id_a' => $request['cursoid'],
                'id_m' => $asig->id,
            ]);
        });


        $modulos = $this->extraerModulos($request['cursoid']);

        return redirect()->route('modulos.materia.show', ['id_a' => $request->input('cursoid')])->with('status', 'Módulo creado exitosamente.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function extraerModulos($id)
    {

        $modulos = DB::table('modulos')
            ->join('asig_modulos', 'asig_modulos.id_m', '=', 'modulos.id')
            ->select('modulos.*')
            ->where('asig_modulos.id_a', '=', $id)
            ->get();
        return $modulos;
    }
    public function show($id_a)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modulo $modulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modulo $modulo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modulo $modulo)
    {
        //
    }

    public function AsignarEstudiantesAprobados($id_pm, $id_a)
    {
        $id_m = paralelo_modulo::find($id_pm)->id_m;


        $modulo = Modulo::find($id_m);

        if ($modulo->ultimo_modulo == 0) {
            if ($modulo) {

                $nombreM = $modulo->nombreM;

                // Usar expresión regular para separar texto y número
                preg_match('/(\D+)\s(\d+)/', $nombreM, $matches);

                $mod = $matches[1] ?? ''; // "Modulo"
                $num = $matches[2] ?? ''; // "1"

                $moduloSiguiente = $mod . ' ' . $num + 1;
            } else {
                return response()->json(['message' => 'No Autorizado'], 404);

            }



            // Paso 2: Verificar si existe  un id_modulo en asigModulo con id_a específico
            // Obtener el conjunto de IDs desde Modulo
            $ids = Modulo::where('nombreM', $moduloSiguiente)->pluck('id');

            // Verificar que $ids no esté vacío antes de realizar la consulta
            if ($ids->isNotEmpty()) {
                // Buscar en asigModulo y extraer los IDs 
                $id_modsiguiente = asigModulo::where('id_a', $id_a)
                    ->whereIn('id_m', $ids)
                    ->pluck('id_m');

                // Verificar si $exists está vacío
                if ($id_modsiguiente->isEmpty()) {
                    return response()->json(['message' => 'No se encontrò el modulo que sigue despues de este'], 404);
                } else {
                    //verificar si existe un paralelo creado para el modulo siguiente 
                    $ParaleloModuloSiguiente = paralelo_modulo::where('id_m', $id_modsiguiente)
                        ->first();

                    // Obtener el nombre de paralelo mediante paralelo modulo segun su id


                    $paralelo = $ParaleloModuloSiguiente->paralelo;

                    if ($ParaleloModuloSiguiente !== null) {
                        $inscritosCount = 0;
                        $registrosExistentes = Estudiantes_asignacion_paramodulo::where('id_pm', $id_pm)->get();

                        foreach ($registrosExistentes as $registro) {
                            if ($registro->nota >= 51) {

                                $asigParaMod = Estudiantes_asignacion_paramodulo::where('id_u', $registro->id_u)
                                    ->where('id_a', $registro->id_a)
                                    ->where('id_pm', $ParaleloModuloSiguiente->id)->get();

                                if ($asigParaMod->isNotEmpty()) {

                                    return response()->json(
                                        [
                                            'message' => 'Ya Realizó la Asignación de este estudiante.',
                                            'status' => 'error',
                                            'title' => 'Estudiante Asignado'
                                        ],
                                        404
                                    );
                                }

                                $registroNuevo = Estudiantes_asignacion_paramodulo::create([
                                    'id_u' => $registro->id_u,
                                    'id_a' => $registro->id_a,
                                    'id_pm' => $ParaleloModuloSiguiente->id,
                                    'activo' => 'inactivo',
                                    'nota' => 0

                                ]);

                                $user = User::find($registro->id_u);
                                $this->AsignacionRepository->RegistrarPago($registroNuevo->id, $user);
                                $inscritosCount++;
                            }
                        }

                        if ($inscritosCount > 0) {
                            $ParaleloModuloSiguiente->inscritos = $inscritosCount;
                            $ParaleloModuloSiguiente->save();
                        }
                        return response()->json(['message' => 'Los estudiantes Aprobados se registraron correctamente, se les envio la notificacion por sistema y correo', 'status' => 'success', 'title' => 'Asinación Exitosa'], 404);
                    } else {
                        return response()->json(['message' => 'El ' . $moduloSiguiente . ' no cuenta con un paralelo ' . $paralelo->nombre . 'creado', 'status' => 'error', 'title' => 'No existe Paralelo'], 404);
                    }
                }
            } else {

                $asignacion = Estudiantes_asignacion_paramodulo::where('id_pm', $id_pm)->first();
                $materia = Asignacion::find($asignacion->id_a);
                $user = Auth::user();
                $paralelo_modulo = paralelo_modulo::find($id_pm);
                $paralelo_modulo->activo = 1;
                $paralelo_modulo->save();

                $adminUsers = User::role('admin')->get();
                foreach ($adminUsers as $adminUser) {
                    $adminUser->notify(new ModuloNoexiste($user, $materia, $moduloSiguiente, $nombreM));
                }

                return response()->json(['message' => 'El ' . $moduloSiguiente . ' no existe, se envio una notificacion a los administradores, se le notificará para que vuelva a intentarlo.', 'status' => 'error', 'title' => 'Hubo un problema'], 404);
            }

        } else {
            $registros = Estudiantes_asignacion_paramodulo::where('id_a', $id_a)->get();
            $cant_modulos = asigModulo::where('id_a', $id_a)->count();

            // Inicializar un arreglo para almacenar la suma de notas y promedios por estudiante
            $notasPorEstudiante = [];

            // Iterar sobre los registros para sumar las notas por estudiante
            foreach ($registros as $registro) {
                $estudianteId = $registro->id_u; // Obtener el ID del estudiante
                $nota = $registro->nota; // Obtener la nota del estudiante

                // Si el estudiante ya tiene una suma acumulada, sumamos la nueva nota
                if (isset($notasPorEstudiante[$estudianteId])) {
                    $notasPorEstudiante[$estudianteId]['nota_final_materia'] += $nota; // Sumar la nota
                } else {
                    // Si es la primera vez que encontramos este estudiante, inicializamos su suma
                    $notasPorEstudiante[$estudianteId] = [
                        'nota_final_materia' => $nota, // Inicializar la suma de las notas
                        'promedio' => 0 // Inicializar el promedio
                    ];
                }
            }

            // Ahora iteramos para calcular el promedio por módulo para cada estudiante
            foreach ($notasPorEstudiante as &$estudiante) {
                if ($cant_modulos > 0) {
                    // Calcular el promedio dividiendo la nota total entre la cantidad de módulos
                    $estudiante['promedio'] = round($estudiante['nota_final_materia'] / $cant_modulos, 1);
                } else {
                    // Si no hay módulos, asignamos un promedio de 0
                    $estudiante['promedio'] = 0;
                }
            }
            // Inicializar un arreglo para almacenar la suma de notas por estudiante
            $notasPorEstudiante = [];

            $cant_modulos = asigModulo::where('id_a', $id_a)->count();

            // Inicializar un arreglo para almacenar la suma de notas y promedios por estudiante
            $notasPorEstudiante = [];

            // Iterar sobre los registros para sumar las notas por estudiante
            foreach ($registros as $registro) {
                $estudianteId = $registro->id_u; // Obtener el ID del estudiante
                $nota = $registro->nota; // Obtener la nota del estudiante

                // Si el estudiante ya tiene una suma acumulada, sumamos la nueva nota
                if (isset($notasPorEstudiante[$estudianteId])) {
                    $notasPorEstudiante[$estudianteId]['nota_final_materia'] += $nota; // Sumar la nota
                } else {
                    // Si es la primera vez que encontramos este estudiante, inicializamos su suma
                    $notasPorEstudiante[$estudianteId] = [
                        'nota_final_materia' => $nota, // Inicializar la suma de las notas
                        'promedio' => 0 // Inicializar el promedio
                    ];
                }
            }

            // Ahora iteramos para calcular el promedio por módulo para cada estudiante
            foreach ($notasPorEstudiante as &$estudiante) {
                if ($cant_modulos > 0) {
                    // Calcular el promedio dividiendo la nota total entre la cantidad de módulos
                    $estudiante['promedio'] = round($estudiante['nota_final_materia'] / $cant_modulos, 1);
                } else {
                    // Si no hay módulos, asignamos un promedio de 0
                    $estudiante['promedio'] = 0;
                }
            }

            foreach ($notasPorEstudiante as $estudianteId => $datos) {
                // Solo guardar si la nota final es mayor o igual a 51
                if ($datos['promedio'] >= 51) {
                    EstudianteFinalizado::updateOrCreate(
                        [
                            'user_id' => $estudianteId, // Buscar por ID del estudiante
                            'id_a' => $id_a, // ID de la asignación de módulo
                        ],
                        [
                            'nota' => $datos['promedio'], // Nota calculada
                            'aprobado' => 1, // Guardar 1 si la nota es >= 51
                        ]
                    );
                } else {
                    // Si la nota es menor que 51, guardar 0
                    EstudianteFinalizado::updateOrCreate(
                        [
                            'user_id' => $estudianteId, // Buscar por ID del estudiante
                            'id_a' => $id_a, // ID de la asignación de módulo
                        ],
                        [
                            'nota' => $datos['promedio'], // Nota calculada
                            'aprobado' => 0, // Guardar 0 si la nota es menor que 51
                        ]
                    );
                }
            }

            // En tu controlador o lógica donde envías el correo
            $materia = Asignacion::find($id_a);
            $estudiantes = EstudianteFinalizado::where('id_a', $id_a)->get();
            foreach ($estudiantes as $estudiante) {
                if ($estudiante->nota >= 51) {

                    $user = User::find($estudiante->user_id);
                    $nombre_estudiante = $user->usuario_nombres . ' ' . $user->usuario_app . ' ' . $user->usuario_apm;

                    $mensaje = "¡Enhorabuena, " . $nombre_estudiante . "!" . "\n\n";
                    $mensaje .= "Felicitaciones por completar el curso de " . $materia->nombre . ". Estamos increíblemente orgullosos de tu esfuerzo y dedicación. El camino hacia el éxito está lleno de aprendizaje y superación, y tú has demostrado que con esfuerzo, pasión y perseverancia, ¡todo es posible!";

                    $detalle = [
                        'titulo' => '¡Felicidades TECNOAMIGO!',
                        'cuerpo' => $mensaje,
                        'nota' => $estudiante->nota,
                        'enlace' => route('generar_certificados', ['user_id' => Crypt::encrypt($user->id), 'id_a' => Crypt::encrypt($id_a)])

                    ];

                    Mail::to($user->email)->send(new CorreoFinaliza($detalle));
                }
            }

            return response()->json(['message' => '¡Felicidades! Has completado todos los módulos con tus estudiantes. Los estudiantes aprobados han recibido su certificado automáticamente por correo electrónico. Si lo deseas, también puedes generar y enviar manualmente los certificados desde la administración en cualquier momento. ¡Sigue así!'], 404);

        }
    }


    public function AsignarEstudiantesIndividual($id_u, $id_m, $id_p)
    {



        $id_a = Estudiantes_asignacion_paramodulo::where('id_u', $id_u)
            ->where('id_pm', $id_p)->first()->id_a;

        $modulo = Modulo::find($id_m);

        if ($modulo->ultimo_modulo == 0) {
            if ($modulo) {

                $nombreM = $modulo->nombreM;

                // Usar expresión regular para separar texto y número
                preg_match('/(\D+)\s(\d+)/', $nombreM, $matches);

                $mod = $matches[1] ?? ''; // "Modulo"
                $num = $matches[2] ?? ''; // "1"

                $moduloSiguiente = $mod . ' ' . $num + 1;
            } else {
                return response()->json(['message' => 'No Autorizado'], 404);

            }



            // Paso 2: Verificar si existe  un id_modulo en asigModulo con id_a específico
            // Obtener el conjunto de IDs desde Modulo
            $ids = Modulo::where('nombreM', $moduloSiguiente)->pluck('id');

            // Verificar que $ids no esté vacío antes de realizar la consulta
            if ($ids->isNotEmpty()) {
                // Buscar en asigModulo y extraer los IDs 
                $id_modsiguiente = asigModulo::where('id_a', $id_a)
                    ->whereIn('id_m', $ids)
                    ->pluck('id_m');

                // Verificar si $exists está vacío
                if ($id_modsiguiente->isEmpty()) {
                    return response()->json(['message' => 'No se encontrò el modulo que sigue despues de este'], 404);
                } else {
                    //verificar si existe un paralelo creado para el modulo siguiente 
                    $ParaleloModuloSiguiente = paralelo_modulo::where('id_m', $id_modsiguiente)
                        ->first();

                    // Obtener el nombre de paralelo mediante paralelo modulo segun su id


                    $paralelo = $ParaleloModuloSiguiente->paralelo;

                    if ($ParaleloModuloSiguiente !== null) {
                        $inscritosCount = 0;
                        $registrosExistentes = Estudiantes_asignacion_paramodulo::where('id_u', $id_u)->where('id_pm', $id_p)->get();
                        $para_mod = paralelo_modulo::find($id_p)->activo;
                        if ($para_mod == 1) {
                            return response()->json(
                                [
                                    'message' => 'El Paralelo correspondiente a este modulo aun esta activo, primero debe finalizar para realizar la asignación',
                                    'status' => 'error',
                                    'title' => 'Error al Asignar'
                                ],
                                404
                            );

                        }


                        foreach ($registrosExistentes as $registro) {
                            if ($registro->nota >= 51) {

                                $asigParaMod = Estudiantes_asignacion_paramodulo::where('id_u', $registro->id_u)
                                    ->where('id_a', $registro->id_a)
                                    ->where('id_pm', $ParaleloModuloSiguiente->id)->get();

                                if ($asigParaMod->isNotEmpty()) {
                                    return response()->json(
                                        [
                                            'message' => 'Ya Realizó la Asignación de este estudiante.',
                                            'status' => 'error',
                                            'title' => 'Estudiante Asignado'
                                        ],
                                        404
                                    );
                                }
                                $registroNuevo = Estudiantes_asignacion_paramodulo::create([
                                    'id_u' => $registro->id_u,
                                    'id_a' => $registro->id_a,
                                    'id_pm' => $ParaleloModuloSiguiente->id,
                                    'activo' => 'inactivo',
                                    'nota' => 0

                                ]);
                                $user = User::find($registro->id_u);


                                $this->AsignacionRepository->RegistrarPago($registroNuevo->id, $user);
                                $inscritosCount++;
                            }
                        }

                        if ($inscritosCount > 0) {
                            $ParaleloModuloSiguiente->inscritos = $inscritosCount;
                            $ParaleloModuloSiguiente->save();
                        }


                        return response()->json([
                            'message' => 'El estudiante se registró correctamente, se le envió  notificacion por sistema y correo',
                            'status' => 'success',
                            'title' => 'Asinación Exitosa'
                        ], 404);
                    } else {
                        return response()->json(['message' => 'El ' . $moduloSiguiente . ' no cuenta con un paralelo ' . $paralelo->nombre . 'creado', 'status' => 'error', 'title' => 'No existe Paralelo'], 404);
                    }
                }
            } else {

                $asignacion = Estudiantes_asignacion_paramodulo::where('id_pm', $id_p)->first();
                $materia = Asignacion::find($asignacion->id_a);
                $user = Auth::user();
                $paralelo_modulo = paralelo_modulo::find($id_p);
                $paralelo_modulo->activo = 1;
                $paralelo_modulo->save();

                $adminUsers = User::role('admin')->get();
                foreach ($adminUsers as $adminUser) {
                    $adminUser->notify(new ModuloNoexiste($user, $materia, $moduloSiguiente, $nombreM));
                }

                return response()->json(['message' => 'El ' . $moduloSiguiente . ' no existe, se envio una notificacion a los administradores, se le notificará para que vuelva a intentarlo.', 'status' => 'error', 'title' => 'Hubo un problema'], 404);
            }

        } else {
            $registros = Estudiantes_asignacion_paramodulo::where('id_a', $id_a)->get();
            $cant_modulos = asigModulo::where('id_a', $id_a)->count();

            // Inicializar un arreglo para almacenar la suma de notas y promedios por estudiante
            $notasPorEstudiante = [];

            // Iterar sobre los registros para sumar las notas por estudiante
            foreach ($registros as $registro) {
                $estudianteId = $registro->id_u; // Obtener el ID del estudiante
                $nota = $registro->nota; // Obtener la nota del estudiante

                // Si el estudiante ya tiene una suma acumulada, sumamos la nueva nota
                if (isset($notasPorEstudiante[$estudianteId])) {
                    $notasPorEstudiante[$estudianteId]['nota_final_materia'] += $nota; // Sumar la nota
                } else {
                    // Si es la primera vez que encontramos este estudiante, inicializamos su suma
                    $notasPorEstudiante[$estudianteId] = [
                        'nota_final_materia' => $nota, // Inicializar la suma de las notas
                        'promedio' => 0 // Inicializar el promedio
                    ];
                }
            }

            // Ahora iteramos para calcular el promedio por módulo para cada estudiante
            foreach ($notasPorEstudiante as &$estudiante) {
                if ($cant_modulos > 0) {
                    // Calcular el promedio dividiendo la nota total entre la cantidad de módulos
                    $estudiante['promedio'] = round($estudiante['nota_final_materia'] / $cant_modulos, 1);
                } else {
                    // Si no hay módulos, asignamos un promedio de 0
                    $estudiante['promedio'] = 0;
                }
            }
            // Inicializar un arreglo para almacenar la suma de notas por estudiante
            $notasPorEstudiante = [];

            $cant_modulos = asigModulo::where('id_a', $id_a)->count();

            // Inicializar un arreglo para almacenar la suma de notas y promedios por estudiante
            $notasPorEstudiante = [];

            // Iterar sobre los registros para sumar las notas por estudiante
            foreach ($registros as $registro) {
                $estudianteId = $registro->id_u; // Obtener el ID del estudiante
                $nota = $registro->nota; // Obtener la nota del estudiante

                // Si el estudiante ya tiene una suma acumulada, sumamos la nueva nota
                if (isset($notasPorEstudiante[$estudianteId])) {
                    $notasPorEstudiante[$estudianteId]['nota_final_materia'] += $nota; // Sumar la nota
                } else {
                    // Si es la primera vez que encontramos este estudiante, inicializamos su suma
                    $notasPorEstudiante[$estudianteId] = [
                        'nota_final_materia' => $nota, // Inicializar la suma de las notas
                        'promedio' => 0 // Inicializar el promedio
                    ];
                }
            }

            // Ahora iteramos para calcular el promedio por módulo para cada estudiante
            foreach ($notasPorEstudiante as &$estudiante) {
                if ($cant_modulos > 0) {
                    // Calcular el promedio dividiendo la nota total entre la cantidad de módulos
                    $estudiante['promedio'] = round($estudiante['nota_final_materia'] / $cant_modulos, 1);
                } else {
                    // Si no hay módulos, asignamos un promedio de 0
                    $estudiante['promedio'] = 0;
                }
            }

            foreach ($notasPorEstudiante as $estudianteId => $datos) {
                // Solo guardar si la nota final es mayor o igual a 51
                if ($datos['promedio'] >= 51) {
                    EstudianteFinalizado::updateOrCreate(
                        [
                            'user_id' => $estudianteId, // Buscar por ID del estudiante
                            'id_a' => $id_a, // ID de la asignación de módulo
                        ],
                        [
                            'nota' => $datos['promedio'], // Nota calculada
                            'aprobado' => 1, // Guardar 1 si la nota es >= 51
                        ]
                    );
                } else {
                    // Si la nota es menor que 51, guardar 0
                    EstudianteFinalizado::updateOrCreate(
                        [
                            'user_id' => $estudianteId, // Buscar por ID del estudiante
                            'id_a' => $id_a, // ID de la asignación de módulo
                        ],
                        [
                            'nota' => $datos['promedio'], // Nota calculada
                            'aprobado' => 0, // Guardar 0 si la nota es menor que 51
                        ]
                    );
                }
            }

            // En tu controlador o lógica donde envías el correo
            $materia = Asignacion::find($id_a);
            $estudiantes = EstudianteFinalizado::where('id_a', $id_a)->get();
            foreach ($estudiantes as $estudiante) {
                if ($estudiante->nota >= 51) {

                    $user = User::find($estudiante->user_id);
                    $nombre_estudiante = $user->usuario_nombres . ' ' . $user->usuario_app . ' ' . $user->usuario_apm;

                    $mensaje = "¡Enhorabuena, " . $nombre_estudiante . "!" . "\n\n";
                    $mensaje .= "Felicitaciones por completar el curso de " . $materia->nombre . ". Estamos increíblemente orgullosos de tu esfuerzo y dedicación. El camino hacia el éxito está lleno de aprendizaje y superación, y tú has demostrado que con esfuerzo, pasión y perseverancia, ¡todo es posible!";

                    $detalle = [
                        'titulo' => '¡Felicidades TECNOAMIGO!',
                        'cuerpo' => $mensaje,
                        'nota' => $estudiante->nota,
                        'enlace' => route('generar_certificados', ['user_id' => Crypt::encrypt($user->id), 'id_a' => Crypt::encrypt($id_a)])

                    ];

                    Mail::to($user->email)->send(new CorreoFinaliza($detalle));
                }
            }

            return response()->json(['message' => '¡Felicidades! Has completado todos los módulos con tus estudiantes. Los estudiantes aprobados han recibido su certificado automáticamente por correo electrónico. Si lo deseas, también puedes generar y enviar manualmente los certificados desde la administración en cualquier momento. ¡Sigue así!'], 404);

        }
    }
    function GenerarCertificado($user_id, $id_a)
    {

        $user_id = Crypt::decrypt($user_id);

        $id_a = Crypt::decrypt($id_a);
        // Obtén los datos del estudiante, asignación, y módulos
        $estudiantes = EstudianteFinalizado::where('user_id', $user_id)->where('id_a', $id_a)->first();
        $materia = Asignacion::find($id_a);
        $asigModulos = asigModulo::with('modulo')->where('id_a', $id_a)->get();

        // Calcula el tiempo total de la materia
        $totalDuracion = $asigModulos->sum(function ($asigModulo) {
            if ($modulo = $asigModulo->modulo) {
                return array_sum(array_map('intval', preg_split('/\D+/', $modulo->Duracion)));
            }
            return 0;
        });

        // Convierte el tiempo total de duración a texto
        $tiempo_materia = $totalDuracion > 0 ? $this->numeroATexto($totalDuracion) . ($totalDuracion == 1 ? " MES" : " MESES") : '';

        // Obtén los datos del estudiante
        $user = User::find($user_id);
        $nombre_estudiante = $user->usuario_nombres . ' ' . $user->usuario_app . ' ' . $user->usuario_apm;

        // Prepara los datos para la vista
        $data = [
            'materia' => $materia->nombre,
            'tiempo_materia' => $tiempo_materia,
            'nombre_estudiante' => $nombre_estudiante,
            'nota' => $estudiantes->nota
        ];

        // Generar el código QR solo si no existe
        $nombre_imagen = $user->usuario_app . '_' . $user->usuario_apm . '.png';
        $path = 'qr/' . $nombre_imagen;
        if (!Storage::disk('public')->exists($path)) {
            $qrCode = new QrCode(json_encode($data));
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            Storage::disk('public')->put($path, $result->getString());
        }

        // Rutas de las imágenes
        $greenColor = '#4CAF50';
        $imagePath = public_path('storage/certificado.png');
        $logo = public_path('storage/logo_tecnonautas.png');
        $cinta = public_path('storage/cinta.png');
        $qrImagePath = public_path('storage/' . $path);
        $firma = public_path('storage/firmas/gerente.png');

        // Genera el PDF a partir de la vista y pasa los datos
        $pdf = Pdf::loadView('modulos.prueba', compact('data', 'qrImagePath', 'greenColor', 'imagePath', 'firma', 'logo', 'cinta'));

        // Descarga el PDF generado
        return $pdf->stream('certificado_' . $user_id . '.pdf');
    }
    function GenerarCertificado_seguro($user_id, $id_a)
    {


        // Obtén los datos del estudiante, asignación, y módulos
        $estudiantes = EstudianteFinalizado::where('user_id', $user_id)->where('id_a', $id_a)->first();
        $materia = Asignacion::find($id_a);
        $asigModulos = asigModulo::with('modulo')->where('id_a', $id_a)->get();

        // Calcula el tiempo total de la materia
        $totalDuracion = $asigModulos->sum(function ($asigModulo) {
            if ($modulo = $asigModulo->modulo) {
                return array_sum(array_map('intval', preg_split('/\D+/', $modulo->Duracion)));
            }
            return 0;
        });

        // Convierte el tiempo total de duración a texto
        $tiempo_materia = $totalDuracion > 0 ? $this->numeroATexto($totalDuracion) . ($totalDuracion == 1 ? " MES" : " MESES") : '';

        // Obtén los datos del estudiante
        $user = User::find($user_id);
        $nombre_estudiante = $user->usuario_nombres . ' ' . $user->usuario_app . ' ' . $user->usuario_apm;

        // Prepara los datos para la vista
        $data = [
            'materia' => $materia->nombre,
            'tiempo_materia' => $tiempo_materia,
            'nombre_estudiante' => $nombre_estudiante,
            'nota' => $estudiantes->nota
        ];

        // Generar el código QR solo si no existe
        $nombre_imagen = $user->usuario_app . '_' . $user->usuario_apm . '.png';
        $path = 'qr/' . $nombre_imagen;
        if (!Storage::disk('public')->exists($path)) {
            $qrCode = new QrCode(json_encode($data));
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            Storage::disk('public')->put($path, $result->getString());
        }

        // Rutas de las imágenes
        $greenColor = '#4CAF50';
        $imagePath = public_path('storage/certificado.png');
        $logo = public_path('storage/logo_tecnonautas.png');
        $cinta = public_path('storage/cinta.png');
        $qrImagePath = public_path('storage/' . $path);
        $firma = public_path('storage/firmas/gerente.png');

        // Genera el PDF a partir de la vista y pasa los datos
        $pdf = Pdf::loadView('modulos.prueba', compact('data', 'qrImagePath', 'greenColor', 'imagePath', 'firma', 'logo', 'cinta'));

        // Descarga el PDF generado
        return $pdf->stream('certificado_' . $user_id . '.pdf');
    }
    function generar_pdf($data, $qrImagePath, $path)
    {
        $pdf = new TCPDF();


        $greenColor = '#4CAF50';


        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(5, 0, 5);


        $pdf->AddPage('L', 'A4');


        $html = view('modulos.prueba', compact('data', 'qrImagePath', 'imagePath', 'greenColor'))->render();


        $pdf->writeHTML($html, true, false, true, false, '');


        $pdf->Output('reporte.pdf', 'I');
    }

    function numeroATexto($numero)
    {
        $numerosTexto = [
            0 => 'CERO',
            1 => 'UN',
            2 => 'DOS',
            3 => 'TRES',
            4 => 'CUATRO',
            5 => 'CINCO',
            6 => 'SEIS',
            7 => 'SIETE',
            8 => 'OCHO',
            9 => 'NUEVE',
            10 => 'DIEZ',
            11 => 'ONCE',
            12 => 'DOCE',
            13 => 'TRECE',
            14 => 'CATORCE',
            15 => 'QUINCE',
            16 => 'DIECISEIS',
            17 => 'DIECISIETE',
            18 => 'DIECIOCHO',
            19 => 'DIECINUEVE',
            20 => 'VEINTE',
            21 => 'VEINTIUNO',
            22 => 'VEINTIDOS',
            23 => 'VEINTITRES',
            24 => 'VEINTICUATRO',
            25 => 'VEINTICINCO',
            // Puedes seguir añadiendo más números si lo deseas
        ];

        // Si el número está en el array, devolver su texto
        if (isset($numerosTexto[$numero])) {
            return $numerosTexto[$numero];
        }

        // Si el número es mayor a 25, devolver el número tal cual (o agregar más reglas)
        return $numero;
    }
}
