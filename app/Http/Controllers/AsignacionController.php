<?php

namespace App\Http\Controllers;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use DB;
use App\Traits\Base64ToFile;
use App\Interfaces\AsignacionInterface;
use App\Models\Estudiantes_asignacion_paramodulo;
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
        //
    }
    public function showI()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Inscripcion Materias', 'url' => route('asignacion.index')],

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

        return response()->json($data);
    }

    public function inscripcionpago(Request $request)
    {

        $this->userId = Auth::id();
        $this->AsignacionRepository->InscripcionPago($this->userId, $request);

        return redirect()->route('asignacion.index')->with('status', 'se ha inscrito exitosamente!');
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
        if (!auth()->user()->can('asignacion.delete')) {
            abort(403, 'No tienes permiso para eliminar este curso.');
        }
        // Buscar la asignación por ID
        $asignacion = Asignacion::findOrFail($id);

        // Eliminar el registro
        $asignacion->delete();

        // Redirigir con un mensaje de éxito
        return redirect()->route('asignacion.index')
            ->with('success', 'Curso eliminado correctamente.');
    }
}
