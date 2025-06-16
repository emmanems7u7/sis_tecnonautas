<?php

namespace App\Http\Controllers;

use App\Models\Estudiantes_asignacion_paramodulo;
use App\Models\tareas_estudiante;
use App\Models\evaluacionCompleta;
use App\Models\Tema;
use App\Models\Tarea;
use App\Models\Asignacion;

use App\Interfaces\TareasInterface;
use App\Interfaces\EvaluacionInterface;
use App\Models\Evaluacion;
use App\Models\admpago;
use App\Models\ContenidoTema;
use App\Models\Temas_paramodulo;
use App\Models\asigModulo;
use App\Models\paralelo_modulo;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Traits\Base64ToFile;
use DB;
use Illuminate\Support\Facades\Auth;

class TemaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    protected $TareasRepository;
    protected $EvaluacionRepository;

    public function __construct(TareasInterface $TareasRepository, EvaluacionInterface $EvaluacionRepository)
    {
        $this->TareasRepository = $TareasRepository;
        $this->EvaluacionRepository = $EvaluacionRepository;

    }
    public function index()
    {
        //
    }
    public function obtenerTemas($id_m)
    {

        $temasParamodulos = Temas_paramodulo::with(['paraleloModulo', 'tema'])

            ->whereHas('paraleloModulo', function ($query) use ($id_m) {
                $query->where('id_m', $id_m);
            })

            ->get();


        $resultados = [];

        $resultados = [];
        $temasProcesados = []; // Array para evitar duplicados por ID de tema

        foreach ($temasParamodulos as $temasParamodulo) {
            $tema = $temasParamodulo->tema;
            if (!$tema)
                continue;

            // Evitar duplicados
            if (in_array($tema->id, $temasProcesados)) {
                continue;
            }

            $contenidos = $tema->contenidoTemas()->get();

            $contenidosArray = [];
            foreach ($contenidos as $contenido) {
                $contenidosArray[] = $contenido->nombre;
            }

            $resultados[] = [
                'id_tema' => $tema->id,
                'nombre_tema' => $tema->nombre,
                'contenidos' => $contenidosArray
            ];

            $temasProcesados[] = $tema->id; // Marcar el tema como ya procesado
        }



        return response()->json($resultados);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_m)
    {

        return view('temas.create', ['id_m' => $id_m,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



        try {
            // Validación con mensajes personalizados
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'id_pm' => 'required|integer|exists:paralelo_modulos,id',
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser un texto.',
                'nombre.max' => 'El nombre no debe superar los 255 caracteres.',
                'id_pm.required' => 'El parámetro del módulo es obligatorio.',
                'id_pm.integer' => 'El parámetro del módulo debe ser un número.',
                'id_pm.exists' => 'El parámetro del módulo seleccionado no existe.',
            ]);


            $tema = Tema::create([
                'nombre' => $validated['nombre'],
            ]);

            $temamod = Temas_paramodulo::create([
                'id_t' => $tema->id,
                'id_pm' => $validated['id_pm'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Tema registrado correctamente',
                'data' => [
                    'tema' => $tema,
                    'temamod' => $temamod
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], 422);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function show($id_pm, $id_m)
    {
        // dd($id_pm);




        $userId = Auth::id();
        $dato = paralelo_modulo::where('id_m', $id_m)->where('id', $id_pm)->get();

        $asignacion = asigModulo::where('id_m', $id_m)->first();
        $portada = Asignacion::where('id', $asignacion->id_a)->select('portada')->first();
        $d = paralelo_modulo::find($id_pm);
        $id_a = asigModulo::where('id_m', $d->id_m)->first()->id_a;

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Modulos', 'url' => route('modulos.materia.show', ['id_n' => 0, 'id_a' => $id_a])],
        ];

        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('profesor')) {
            $breadcrumb[] = ['name' => 'Paralelos', 'url' => route('Paralelos.modulos.show', ['id_m' => $id_m, 'id_a' => $id_a])];
        }

        $breadcrumb[] = ['name' => 'Contenido del Modulo', 'url' => route('home')];

        $user = Auth::user();
        $rol = $user->role;
        if ($rol == 'estudiante') {
            $apm = Estudiantes_asignacion_paramodulo::where('id_u', $userId)->where('id_pm', $d->id)->first();
            $estado = $apm->activo;
        } else {
            $estado = '';
        }

        if (!$dato->isEmpty()) {
            $temas = Temas_paramodulo::join('temas as t', 't.id', '=', 'temas_paramodulos.id_t')
                ->where('temas_paramodulos.id_pm', $id_pm)
                ->select('t.id', 't.nombre')
                ->get();
            $contenidos = ContenidoTema::all();
            $tareas = Tarea::with('tareasEstudiantes')
                ->where('id_pm', $id_pm)
                ->orderBy('created_at', 'desc')
                ->paginate(3);

            $tareasE = tareas_estudiante::where('user_id', $userId)->get();

            $evaluaciones = Evaluacion::where('id_pm', $id_pm)->get();
            $evaluacionE = EvaluacionCompleta::where('id_u', $userId)->get()->keyBy('id_e');

            return view('temas.show', compact(
                'portada',
                'temas',
                'id_pm',
                'id_m',
                'id_a',
                'tareas',
                'tareasE',
                'contenidos',
                'evaluaciones',
                'evaluacionE',
                'estado',
                'breadcrumb'
            ));
        } else {
            return view('temas.show', [
                'portada' => $portada,
                'temas' => null,
                'id_pm' => $id_pm,
                'id_a' => $id_a,
                'contenidos' => null,
                'evaluaciones' => null,
                'evaluacionE' => null,
                'breadcrumb' => $breadcrumb
            ]);
        }
    }
    public function admin($id, $id_m, $id_p)
    {




        $breadcrumb = [
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Modulos', 'url' => route('modulos.materia.show', ['id_a' => $id])],
            ['name' => 'Modulos', 'url' => route('Paralelos.modulos.show', ['id_m' => $id_m, 'id_a' => $id])],
            ['name' => 'Administrar', 'url' => route('home')],


        ];
        $pagos = admpago::all();

        $materia = asigModulo::join('modulos as m', 'asig_modulos.id_m', '=', 'm.id')
            ->join('asignacions as ai', 'asig_modulos.id_a', '=', 'ai.id')
            ->select('m.nombreM', 'ai.nombre')
            ->where('asig_modulos.id_a', $id)
            ->where('asig_modulos.id_m', $id_m)
            ->first();




        return view('modulos.admin', [
            'pagos' => $pagos,
            'materia' => $materia,
            'id_a' => $id,
            'id_m' => $id_m,
            'id_p' => $id_p,
            'breadcrumb' => $breadcrumb
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function edit(Tema $tema)
    {
        //
    }
    public function storeTemasContenidos($id_t, $id_pm)
    {
        Temas_paramodulo::create([

            'id_t' => $id_t,
            'id_pm' => $id_pm,


        ]);
        return response()->json(['message' => 'Agregado exitosamente']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tema $tema)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function eliminarTema($id)
    {
        DB::beginTransaction();

        try {
            // Encuentra el tema por ID junto con relaciones
            $tema = Tema::with(['temasParamodulos', 'contenidoTemas'])->findOrFail($id);

            // Elimina las relaciones en temas_paramodulos
            foreach ($tema->temasParamodulos as $temaParamodulo) {
                $temaParamodulo->delete();
            }

            // Elimina los contenidos asociados
            foreach ($tema->contenidoTemas as $contenido) {
                // Eliminar el archivo asociado, si existe
                if ($contenido->tipo === 'documento' && file_exists(public_path('storage/' . $contenido->ruta))) {
                    unlink(public_path('storage/' . $contenido->ruta));
                }

                // Elimina el contenido
                $contenido->delete();
            }

            // Elimina el tema
            $tema->delete();

            DB::commit();

            return redirect()->back()->with('status', 'Tema eliminado con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar el tema: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el tema.');
        }
    }

    public function Temacontenido($id)
    {
        return view('temas.ver');
    }
    public function finalizar($id_pm)
    {

        $estudiantesEvaluaciones = $this->EvaluacionRepository->GetEvaluacionesEstudiantes($id_pm);

        $estudiantesTareas = $this->TareasRepository->GetTareasEstudiantes($id_pm);

        if (empty($estudiantesEvaluaciones)) {
            return response()->json([
                'title' => 'El paralelo no se finalizó ',
                'status' => 'error',
                'message' => 'No se pudieron obtener las evaluaciones, verifique si al menos existe una evaluación',
                'button' => 0
            ], 500);
        }
        if (empty($estudiantesTareas)) {
            return response()->json([
                'title' => 'El paralelo no se finalizó ',
                'status' => 'error',
                'message' => 'No se pudieron obtener las  tareas, verifique si al menos existe una tarea creada',
                'button' => 0
            ], 500);
        }

        // Iterar sobre las evaluaciones para combinar con tareas
        foreach ($estudiantesEvaluaciones as $evaluacion) {
            // Buscar al estudiante en el array $estudiantesTareas
            foreach ($estudiantesTareas as &$estudiante) {
                if ($estudiante['user_id'] == $evaluacion['user_id']) {
                    // Añadir las tareas al estudiante en el array de evaluaciones
                    $estudiante['evaluaciones'] = $evaluacion['evaluaciones'];
                    break;
                }
            }
        }



        //total de tareas y evaluaciones
        $totaltareas = Tarea::where('id_pm', $id_pm)->count();
        $totalEvaluaciones = Evaluacion::where('id_pm', $id_pm)->count();

        if ($totalEvaluaciones < 0) {
            return response()->json([
                'title' => 'El paralelo no se finalizó ',
                'status' => 'error',
                'message' => 'Error al contar  evaluaciones.',
                'button' => 0
            ], 500);
        }
        if ($totaltareas < 0) {
            return response()->json([
                'title' => 'El paralelo no se finalizó ',
                'status' => 'error',
                'message' => 'Error al contar tareas ',
                'button' => 0
            ], 500);
        }

        $totalProm = $totaltareas + $totalEvaluaciones;

        // Iterar sobre el array de estudiantes
        foreach ($estudiantesTareas as &$estudiante) {
            // Inicializar sumas locales
            $sumaNotasTareas = 0;
            $sumaNotasEvaluaciones = 0;

            // Calcular suma de notas de tareas
            foreach ($estudiante['tareas'] as $tarea) {
                $sumaNotasTareas += $tarea['nota'];
            }

            // Calcular suma de notas de evaluaciones
            foreach ($estudiante['evaluaciones'] as $evaluacion) {
                $sumaNotasEvaluaciones += $evaluacion['nota'];
            }

            // Añadir promedios al estudiante

            $estudiante['nota_tareas'] = $sumaNotasTareas;
            $estudiante['nota_evaluaciones'] = $sumaNotasEvaluaciones;
            $estudiante['nota_final'] = round(($estudiante['nota_tareas'] + $estudiante['nota_evaluaciones']) / $totalProm, 1);
        }

        // dd($estudiantesTareas);



        $estasignacion = Estudiantes_asignacion_paramodulo::where('id_pm', $id_pm)->get();

        foreach ($estasignacion as $estudiantes) {

            foreach ($estudiantesTareas as $nota) {
                if ($estudiantes->id_u == $nota['user_id']) {
                    $estudiantes->nota = $nota['nota_final'];
                    $estudiantes->activo = 'inactivo';
                    $estudiantes->save();
                    break;
                }
            }
        }

        $paraleloModulo = paralelo_modulo::find($id_pm);
        if ($paraleloModulo) {
            $paraleloModulo->activo = 0;
            $paraleloModulo->save();

            //return response()->json(['message' => 'El paralelo finalizó correctamente, los estudiantes aprobados se asignaron automaticamente al siguiente modulo correspondiente, se les notificò por sistema y correo'], 200);

            return response()->json([
                'title' => 'El paralelo se finalizó exitosamente',
                'status' => 'success',

                'message' => 'El paralelo finalizó correctamente, ¿desea asignar automaticamente al siguiente modulo a los estudiantes aprobados?',
                'button' => 1
            ], 200);
        } else {
            return response()->json([
                'title' => 'Error al Finalizar',
                'status' => 'error',
                'message' => 'No se encontró el paralelo',
                'button' => 1
            ], 404);
        }
    }





}
