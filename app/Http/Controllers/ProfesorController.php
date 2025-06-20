<?php

namespace App\Http\Controllers;

use App\Models\asignacion_profesor;
use App\Models\Profesor;
use App\Models\Estudio;
use App\Models\Experiencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\NotificationInterface;
use App\Interfaces\UserInterface;
use App\Models\asigModulo;
use App\Models\Asignacion;
use App\Models\Modulo;
use App\Models\Paralelo;
use App\Models\paralelo_modulo;
use App\Models\User;
use App\Exports\ExportPDF;

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
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'horarios', 'url' => route('home')],
        ];

        if ($horariosF == 0) {

            return redirect()->back()->with('error', 'No tienes horarios asignados o no tienes el rol de profesor');

        }
        return view('Personal.horarios', compact('horariosF', 'breadcrumb'));
    }


    private function getDiasSemana()
    {
        return ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
    }

    private function getRangosMediaHora()
    {
        $rangos = [];
        for ($h = 8; $h < 19; $h++) {
            $inicio = sprintf('%02d:00', $h);
            $media = sprintf('%02d:30', $h);
            $fin = sprintf('%02d:00', $h + 1);

            $rangos[] = "$inicio - $media";
            $rangos[] = "$media - $fin";
        }
        return $rangos;
    }

    private function getHorariosPorProfesor($asignaciones)
    {
        $horarios = [];
        foreach ($asignaciones as $asignacion) {
            $profesor = $asignacion->profesor;
            $paralelo = $asignacion->paraleloModulo;
            if (!$paralelo || !$profesor)
                continue;

            $nombre = trim($profesor->usuario_nombres . ' ' . $profesor->usuario_app . ' ' . $profesor->usuario_apm);
            $id = $profesor->id;

            $index = collect($horarios)->search(fn($item) => $item['user_id'] === $id);
            if ($index === false) {
                $horarios[] = [
                    'user_id' => $id,
                    'profesor' => $nombre,
                    'horarios' => [],
                ];
                $index = count($horarios) - 1;
            }

            foreach ($paralelo->horarios as $h) {
                $horarios[$index]['horarios'][] = [
                    'dia' => $h->dias,
                    'inicio' => $h->inicio,
                    'fin' => $h->fin,
                    'paralelo_id' => $paralelo->id,
                    'modulo_id' => $paralelo->id_m,
                ];
            }
        }
        return $horarios;
    }

    private function getHorarioTabla($asignaciones, $diasSemana, $rangos, $coloresPorProfesor)
    {
        $tabla = [];
        foreach ($rangos as $r) {
            $horaClave = substr($r, 0, 5);
            foreach ($diasSemana as $d) {
                $tabla[$horaClave][$d] = [];
            }
        }

        $toMin = fn($h) => sscanf($h, "%d:%d")[0] * 60 + sscanf($h, "%d:%d")[1];

        foreach ($asignaciones as $asignacion) {
            $profesor = $asignacion->profesor;
            $paralelo = $asignacion->paraleloModulo;
            if (!$paralelo || !$profesor)
                continue;

            $nombre = trim($profesor->usuario_nombres . ' ' . $profesor->usuario_app . ' ' . $profesor->usuario_apm);
            $id = $profesor->id;

            foreach ($paralelo->horarios as $h) {
                $inicio = $toMin($h->inicio);
                $fin = $toMin($h->fin);
                $dia = $h->dias;
                if (!in_array($dia, $diasSemana))
                    continue;

                for ($min = $inicio; $min < $fin; $min += 30) {
                    $hKey = sprintf('%02d:%02d', floor($min / 60), $min % 60);
                    $asigMod = asigModulo::where('id_m', $paralelo->id_m)->first();

                    $tabla[$hKey][$dia][] = [
                        'profesor' => $nombre,
                        'modulo_id' => $paralelo->id_m,
                        'asignacion_nombre' => Asignacion::find($asigMod->id_a)->nombre,
                        'modulo_nombre' => Modulo::find($paralelo->id_m)->nombreM,
                        'paralelo_id' => $paralelo->id_p,
                        'paralelo_nombre' => Paralelo::find($paralelo->id_p)->nombre,
                        'color' => $coloresPorProfesor[$id] ?? '#FFFFFF',
                        'inicio' => $h->inicio,
                        'fin' => $h->fin,
                    ];
                }
            }
        }

        return $tabla;
    }


    public function horarios_profesores()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'horarios Profesores', 'url' => route('home')],
        ];

        $diasSemana = $this->getDiasSemana();
        $rangos = $this->getRangosMediaHora();

        $asignaciones = asignacion_profesor::whereHas('paraleloModulo', fn($q) => $q->where('activo', 1))
            ->with([
                'paraleloModulo.horarios',
                'profesor' => fn($q) => $q->select('id', 'usuario_nombres', 'usuario_app', 'usuario_apm'),
            ])->get();

        $horariosPorProfesor = $this->getHorariosPorProfesor($asignaciones);

        $colores = [
            '#FFCDD2',
            '#F8BBD0',
            '#E1BEE7',
            '#D1C4E9',
            '#C5CAE9',
            '#BBDEFB',
            '#B3E5FC',
            '#B2EBF2',
            '#B2DFDB',
            '#C8E6C9',
            '#DCEDC8',
            '#F0F4C3',
            '#FFF9C4',
            '#FFECB3',
            '#FFE0B2',
            '#FFCCBC',
            '#D7CCC8',
            '#CFD8DC',
        ];
        $coloresPorProfesor = [];
        foreach ($horariosPorProfesor as $i => &$prof) {
            $c = $colores[$i % count($colores)];
            $prof['color'] = $c;
            $coloresPorProfesor[$prof['user_id']] = $c;
        }
        unset($prof);

        $horarioTabla = $this->getHorarioTabla($asignaciones, $diasSemana, $rangos, $coloresPorProfesor);

        return view('Personal.horarios_profesores', compact(
            'breadcrumb',
            'horariosPorProfesor',
            'horarioTabla',
            'diasSemana',
            'rangos'
        ));
    }
    public function export_horarios()
    {

        $diasSemana = $this->getDiasSemana();
        $rangos = $this->getRangosMediaHora();

        $asignaciones = asignacion_profesor::whereHas('paraleloModulo', fn($q) => $q->where('activo', 1))
            ->with([
                'paraleloModulo.horarios',
                'profesor' => fn($q) => $q->select('id', 'usuario_nombres', 'usuario_app', 'usuario_apm'),
            ])->get();

        $horariosPorProfesor = $this->getHorariosPorProfesor($asignaciones);

        $colores = [
            '#FFCDD2',
            '#F8BBD0',
            '#E1BEE7',
            '#D1C4E9',
            '#C5CAE9',
            '#BBDEFB',
            '#B3E5FC',
            '#B2EBF2',
            '#B2DFDB',
            '#C8E6C9',
            '#DCEDC8',
            '#F0F4C3',
            '#FFF9C4',
            '#FFECB3',
            '#FFE0B2',
            '#FFCCBC',
            '#D7CCC8',
            '#CFD8DC',
        ];
        $coloresPorProfesor = [];
        foreach ($horariosPorProfesor as $i => &$prof) {
            $c = $colores[$i % count($colores)];
            $prof['color'] = $c;
            $coloresPorProfesor[$prof['user_id']] = $c;
        }
        unset($prof);

        $horarioTabla = $this->getHorarioTabla($asignaciones, $diasSemana, $rangos, $coloresPorProfesor);

        $user = Auth::user();

        $nombre = $user->usuario_nombres . ' ' . $user->usuario_app . ' ' . $user->usuario_apm;

        $hora = now();
        $total = User::role('profesor')->count();

        $path_body = public_path('imagenes/tecnonautas.png');
        $type_body = pathinfo($path_body, PATHINFO_EXTENSION);
        $imgData_body = base64_encode(file_get_contents($path_body));
        $src_body = 'data:image/' . $type_body . ';base64,' . $imgData_body;


        return ExportPDF::exportPdf(
            'Personal.reporte_horarios_prof',

            [
                'horariosPorProfesor' => $horariosPorProfesor,
                'horarioTabla' => $horarioTabla,
                'diasSemana' => $diasSemana,
                'rangos' => $rangos,
                'nombre' => $nombre,
                'hora' => $hora,
                'src_body' => $src_body,
                'total' => $total,
            ]
            ,
            'usuarios',
            false,
            ['orientation' => 'L']
        );

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
