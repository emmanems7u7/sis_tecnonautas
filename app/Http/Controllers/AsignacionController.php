<?php

namespace App\Http\Controllers;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use DB;
use App\Traits\Base64ToFile;
use App\Interfaces\AsignacionInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AsignacionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $AsignacionRepository;
    public $userId;

    public function __construct(AsignacionInterface $AsignacionRepository)
    {
        $this->AsignacionRepository = $AsignacionRepository;
    }
    public function index()
    {

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],
        ];

        $e = $this->AsignacionRepository->GetAsignaciones();


        return view('Materias.show', compact('e', 'breadcrumb'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],

            ['name' => 'Crear Materia', 'url' => route('asignacion.index')],
        ];


        return view('Materias.create', compact('breadcrumb'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'descripcionCorta' => 'required|string|max:500',

            'caracteristicas' => 'required|array|min:1',
            'caracteristicas.*' => 'required|string|max:255',

            'objetivos' => 'required|array|min:1',
            'objetivos.*' => 'required|string|max:255',

            'beneficios' => 'required|array|min:1',
            'beneficios.*' => 'required|string|max:255',

            'tipo' => 'required|in:gratuito,pago',
            'costo' => 'required_if:tipo,pago|nullable|numeric|min:0',

            'img1' => 'nullable|image|max:2048', // máximo 2MB, ajustar según necesidad
            'portada_imagen' => 'nullable|image|max:2048',
        ];

        $messages = [
            'caracteristicas.required' => 'Debe ingresar al menos una característica.',
            'caracteristicas.*.required' => 'Cada característica no puede estar vacía.',
            'objetivos.required' => 'Debe ingresar al menos un objetivo.',
            'objetivos.*.required' => 'Cada objetivo no puede estar vacío.',
            'beneficios.required' => 'Debe ingresar al menos un beneficio.',
            'beneficios.*.required' => 'Cada beneficio no puede estar vacío.',
            'costo.required_if' => 'El costo es obligatorio cuando el tipo es pago.',
            'costo.numeric' => 'El costo debe ser un número válido.',
            'img1.image' => 'El archivo debe ser una imagen válida.',
            'img1.max' => 'La imagen no puede pesar más de 2MB.',
            'portada_imagen.image' => 'El archivo debe ser una imagen válida.',
            'portada_imagen.max' => 'La portada no puede pesar más de 2MB.',
        ];


        $this->AsignacionRepository->GuardarAsignacion($request);
        return redirect()->route('asignacion.index')->with('status', 'se ha creado la materia exitosamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asignacion  $asignacion
     * @return \Illuminate\Http\Response
     */
    public function show(Asignacion $asignacion)
    {


    }
    public function showJ(Asignacion $asignacion)
    {

        $e = $this->AsignacionRepository->GetAsignaciones();


        return response()->json($e);


    }

    public function prueba()
    {

        $b = DB::table('verifica_registros')->select('activo')->where('id', '=', 1)->get();
        dd($b[0]->activo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asignacion  $asignacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Asignacion $asignacion)
    {

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],

            ['name' => 'Editar Materia', 'url' => route('asignacion.index')],
        ];
        return view('Materias.edit', compact('asignacion', 'breadcrumb'));
    }
    public function showI()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Inscripción Materias', 'url' => route('asignacion.index')],

        ];


        $user = Auth::user();

        $asignacionesPago = $this->AsignacionRepository->GetAsignacionPagos();
        $asignacionesGratuitos = $this->AsignacionRepository->GetAsignacionGratuitos();

        return view(
            'inscripciones.show',
            [
                'gratuitos' => $asignacionesGratuitos,
                'pagos' => $asignacionesPago,
                'breadcrumb' => $breadcrumb,
            ]
        );
    }
    public function inscripcion(Request $request)
    {
        $data = $this->AsignacionRepository->GetInscripcion($request);

        if ($data['status'] == 'error') {
            return response()->json(['status' => $data['status'], 'message' => $data['message']], 400);
        }
        return response()->json(['status' => $data['status'], 'message' => '', 'data' => $data['data']], 200);


    }

    public function inscripcion_estudiante()
    {

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Inscripción Estudiante', 'url' => route('asignacion.index')],
        ];

        $asignacionesPago = $this->AsignacionRepository->GetAsignacionPagos();
        $asignacionesGratuitos = $this->AsignacionRepository->GetAsignacionGratuitos();
        $estudiantes = User::role('estudiante')->get();

        return view('inscripciones.index', compact('breadcrumb', 'estudiantes', 'asignacionesPago', 'asignacionesGratuitos'));



    }

    public function inscripcionpago(Request $request)
    {
        if (Auth::check() && Auth::user()->hasRole('estudiante')) {
            $this->userId = Auth::id();

            $this->AsignacionRepository->InscripcionPago($this->userId, $request);

            return redirect()->route('asignacion.index')->with('status', '!se ha inscrito exitosamente!');
        } else {
            return redirect()->back()->with('error', 'Debe tener el rol de estudiante para inscribirse en una materia');

        }
    }

    public function inscripcion_adm(Request $request)
    {


        $user = User::find($request->estudiante);

        if (!$user) {
            return redirect()->back()->with('error', 'Usuario no encontrado');


        }
        if (!$user->hasRole('estudiante')) {

            return redirect()->back()->with('error', 'El usuario debe tener el rol de estudiante para inscribirlo en una materia');

        }

        $data = $this->AsignacionRepository->InscripcionPago($user->id, $request);

        if ($data['status'] == 'error') {
            return redirect()->back()->with('error', $data['message']);
        } elseif ($data['status'] == 'success') {
            return redirect()->back()->with('status', $data['message']);

        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asignacion  $asignacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asignacion $asignacion)
    {
        //
    }

    public function notasEstudiantes(Request $request, Asignacion $asignacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asignacion  $asignacion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $asignacion = Asignacion::findOrFail($id);

        try {
            $asignacion->delete();

            return redirect()->route('asignacion.index')
                ->with('success', 'Curso eliminado correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Verificamos si es error por restricción de clave foránea
            if ($e->getCode() == 23000) {
                return redirect()->route('asignacion.index')
                    ->with('error', 'No se puede eliminar el curso porque tiene registros importantes relacionados.');
            }

            // Otros errores
            return redirect()->route('asignacion.index')
                ->with('error', 'Ocurrió un error al intentar eliminar el curso.');
        }
    }
}
