<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use App\Models\Modulo;
use App\Models\asignacion_profesor;
use App\Models\horario;
use App\Models\asigModulo;
use App\Models\paralelo_modulo;
use Illuminate\Http\Request;
use App\Models\Estudiantes_asignacion_paramodulo;
use App\Models\AsistenciaEstudiante;
use App\Interfaces\ParalelosInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\HorariosInterface;
use DateTime;
use InvalidArgumentException;
class ParaleloController extends Controller
{
    protected $ParaleloRepository;
    protected $UserRepository;
    protected $HorarioRepository;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response

     */
    public function __construct(ParalelosInterface $ParaleloRepository, UserInterface $UserRepository, HorariosInterface $HorarioRepository)
    {
        $this->ParaleloRepository = $ParaleloRepository;
        $this->UserRepository = $UserRepository;
        $this->HorarioRepository = $HorarioRepository;
    }
    public function index()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'configuracion', 'url' => route('admin.configuracion.edit')],
            ['name' => 'Paralelos', 'url' => route('asignacion.index')],

        ];

        $paralelos = Paralelo::all();
        return view('paralelos.index', compact('paralelos', 'breadcrumb'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function GetParalelo($nombre, $id_a, $id_p)
    {

        $datosModulo = Modulo::select('modulos.id', 'nombreM', 'Duracion', 'Descripcion')
            ->join('asig_modulos', 'modulos.id', '=', 'asig_modulos.id_m')
            ->where('nombreM', $nombre)
            ->where('asig_modulos.id_a', $id_a)
            ->first();

        $datosParalelo = $this->ParaleloRepository
            ->GetDatosParaleloI(
                paralelo_modulo::where('id_m', $datosModulo->id)
                    ->where('id', $id_p)
                    ->get(),
                $this->UserRepository
            );

        return response()->json($datosParalelo);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $paralelo = Paralelo::create($request->all());

        return redirect()->route('Paralelos.index')->with('status', 'se registro el paralelo correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paralelo  $paralelo
     * @return \Illuminate\Http\Response
     */
    public function show(Paralelo $paralelo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paralelo  $paralelo
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'configuracion', 'url' => route('admin.configuracion.edit')],
            ['name' => 'Paralelos', 'url' => route('Paralelos.index')],
            ['name' => 'Editar Paralelo', 'url' => route('asignacion.index')],

        ];

        $paralelo = Paralelo::find($id);
        $paralelos = Paralelo::all();

        return view('paralelos.edit', ['breadcrumb' => $breadcrumb, 'paralelos' => $paralelos, 'paralelo' => $paralelo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paralelo  $paralelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $request->validate([
            'nombre' => 'required|string|max:255',
            'cupo' => 'required|integer|min:1',
        ]);


        $paralelo = Paralelo::find($id);


        $paralelo->nombre = $request->nombre;
        $paralelo->cupo = $request->cupo;
        $paralelo->save();


        return redirect()->route('Paralelos.index')->with('status', 'Paralelo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paralelo  $paralelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paralelos = Paralelo::all();

        $paralelo = Paralelo::find($id);


        if (!$paralelo) {
            return redirect()->route('Paralelos.index', ['paralelos' => $paralelos])->with('error', 'El paralelo no existe.');
        }

        $paralelo->delete();

        return redirect()->route('Paralelos.index', ['paralelos' => $paralelos])->with('status', 'Paralelo eliminado exitosamente.');
    }

    public function ShowParelelosModulos($id_a, $id_m)
    {

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Modulos', 'url' => route('modulos.materia.show', ['id_a' => $id_a])],
            ['name' => 'Paralelos', 'url' => route('asignacion.index')],

        ];

        $user = auth()->user();

        $paradisp = $this->ParaleloRepository->getParalelosDisponibles(Paralelo::all(), $id_m);

        $profesores = $this->UserRepository->getProfesores();
        if ($user->hasRole('admin')) {
            $datosParalelos = $this->ParaleloRepository->getDatosParalelos(paralelo_modulo::where('id_m', $id_m)->get(), $this->UserRepository);

        } else {
            if ($user->hasRole('profesor')) {
                $datosParalelos = $this->ParaleloRepository->GetDatosParalelosProfesor(paralelo_modulo::where('id_m', $id_m)->get(), $this->UserRepository, $user->id);
            }
        }

        //dd($datosParalelos);
        return view('paralelos.showParalelosModulos', compact('breadcrumb', 'paradisp', 'profesores', 'id_a', 'id_m', 'datosParalelos'));
    }
    public function editParaleloModulo($id, $id_a, $id_m)
    {

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Materias', 'url' => route('asignacion.index')],
            ['name' => 'Modulos', 'url' => route('modulos.materia.show', ['id_a' => $id_a])],
            ['name' => 'Paralelos', 'url' => route('Paralelos.modulos.show', ['id_m' => $id_m, 'id_a' => $id_a])],
            ['name' => 'Editar', 'url' => route('home')],

        ];

        $paralelo = paralelo_modulo::find($id)->first();

        $nombrepara = paralelo::where('id', $paralelo->id_p)->first();

        $profesores = $this->UserRepository->getProfesores();

        $profesor = $this->UserRepository->getProfesorParalelo($paralelo->id);
        if ($profesor !== null) {
            $id_prof = $profesor->id;
            $nombreProf = $profesor->usuario_nombres . " " . $profesor->usuario_app . " " . $profesor->usuario_apm;
        } else {
            $id_prof = null;
            $nombreProf = null;
        }
        $horariosD = $this->HorarioRepository->agregaHorario($this->HorarioRepository->getHorario($paralelo->id));

        $datos[] =
            [

                'nombre' => $nombrepara->nombre,
                'cupo' => $nombrepara->cupo,
                'inscritos' => $paralelo->inscritos,
                'horarios' => $horariosD,

                'profesorid' => $id_prof,
                'profesor' => $nombreProf,

            ];
        $idp = $paralelo->id;
        return view('paralelos.editparmod', compact('breadcrumb', 'datos', 'profesores', 'idp', 'id_a', 'id_m'));
    }
    public function updateParaleloModulo(Request $request, $id)
    {

        $paralelo = paralelo_modulo::find($id)->first();
        $id_m = $paralelo->id_m;
        $id_a = asigModulo::where('id_m', $id_m)->first();

        foreach ($request->horarioid as $indice => $idh) {

            $horario = horario::find($idh);
            $horario->dias = $request['dia'][$indice];
            $horario->inicio = $request['horaInicio'][$indice];
            $horario->fin = $request['horaFin'][$indice];
            $horario->save();
        }


        $profesor = asignacion_profesor::updateOrCreate(
            ['id_u' => $request['profesor']], // busca un usuario con este email

            ['id_pm' => $id]

        );



        return redirect()->route('Paralelos.modulos.show', ['id_a' => $id_a->id_a, 'id_m' => $id_m])->with('status', 'Paralelo eliminado exitosamente.');

        // return redirect()->back()->with('status','editado correctamente');
    }

    public function storeParaleloModulo(Request $request)
    {

        $validated = $request->validate([
            'paralelo' => 'required|exists:paralelos,id',
            'id_m' => 'required|exists:modulos,id',
            'mes' => 'required|date',

            'dia' => 'required|array|min:1',
            'dia.*' => 'required|string',

            'horaInicio' => 'required|array|min:1',
            'horaInicio.*' => 'required|date_format:H:i',

            'horaFin' => 'required|array|min:1',
            'horaFin.*' => 'required|date_format:H:i|after:horaInicio.*',

            'profesor' => 'required|exists:users,id',
        ]);

        try {
            $paralelo_modulo = paralelo_modulo::create([
                'id_p' => $request->input('paralelo'),
                'id_m' => $request->input('id_m'),
                'activo' => 1,
                'inscritos' => 0,
                'mes' => $request->input('mes'),
            ]);


        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar paralelo modulo' . $e->getMessage());
        }
        try {
            for ($i = 0; $i < count($request->input('dia')); $i++) {
                $horarios = horario::create([
                    'id_mp' => $paralelo_modulo->id,
                    'dias' => $request->input('dia')[$i],
                    'inicio' => $request->input('horaInicio')[$i],
                    'fin' => $request->input('horaFin')[$i],
                ]);

            }
            $this->AsignarHorario($paralelo_modulo->id, $paralelo_modulo->mes);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al horario' . $e->getMessage());

        }

        try {
            $asig_profesor = asignacion_profesor::create([
                'id_pm' => $paralelo_modulo->id,
                'id_u' => $request->input('profesor'),

            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se asigno un Profesor a la materia, Puede asignarlo presionando el boton Editar');

        }

        return back()->with('status', 'se creo registro exitosamente!');
    }
    public function AsignarHorario($id_pm)
    {
        $horarios = Horario::where('id_mp', $id_pm)->get();

        $fecha = paralelo_modulo::find($id_pm)->mes;


        // Crear  objeto DateTime a partir de la fecha 
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
            'Miercoles' => 'Wednesday',
            'Jueves' => 'Thursday',
            'Viernes' => 'Friday',
            'Sábado' => 'Saturday',
            'Domingo' => 'Sunday'
        ];

        // Verificar que los días de la semana solicitados están en el formato correcto
        foreach ($horarios as $dias) {
            if (!array_key_exists($dias->dias, $diasDeLaSemana)) {
                throw new InvalidArgumentException("Día de la semana inválido: $dias->dias");
            }
        }

        // Obtener todos los días del mes que coinciden con los días de la semana especificados
        $diasDelMes = [];
        while ($primerDia <= $ultimoDia) {
            $diaNombre = $primerDia->format('l');
            $diaEspañol = array_search($diaNombre, $diasDeLaSemana);
            foreach ($horarios as $dias) {
                if ($diaEspañol == $dias->dias) {
                    $diasDelMes[] = [
                        'fecha' => $primerDia->format('Y-m-d'),

                    ];
                }
            }
            $primerDia->modify('+1 day');
        }



        $estudiantes = Estudiantes_asignacion_paramodulo::where('id_pm', $id_pm)->get();

        if (empty($estudiantes)) {
            return redirect()->back()->with('warning', 'No se generó la asistencia para estudiantes, debido a que no existen estudiantes inscritos a este paralelo. Puede generarlo manualmente cuando tenga estudiantes inscritos a este paralelo');

        } else {
            foreach ($estudiantes as $estudiante) {
                foreach ($diasDelMes as $dia) {
                    $asistencia = AsistenciaEstudiante::create([
                        'user_id' => $estudiante->id_u,
                        'id_pm' => $id_pm,
                        'fecha' => $dia['fecha'],
                        'asistencia' => 'falta',
                    ]);
                }
            }
            return redirect()->back()->with('status', 'Generado correctamente');

        }




    }

    public function destroy_para_mod($id, $id_a, $id_m)
    {

        $paralelo = paralelo_modulo::where('id', $id)->where('id_p', $id_a)->where('id_m', $id_m)->first();

        if ($paralelo == null) {
            return redirect()->back()->with('error', 'El paralelo no existe o no está asociado a este módulo.');

        }
        $paralelo->delete();

        return redirect()->back()->with('status', 'Eliminado correctamente');
    }
}
