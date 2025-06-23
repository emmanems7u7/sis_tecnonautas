<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;

use App\Models\Modulo;
use App\Models\Asignacion;
use App\Models\paralelo_modulo;
use App\Models\Paralelo;
use Carbon\Carbon;
use App\Models\asignacion_profesor;
use App\Models\asigModulo;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\UserInterface;
use App\Models\evaluacionCompleta;
use App\Models\Evaluacion;
use App\Models\Tarea;
use App\Models\tareas_estudiante;

use App\Interfaces\TareasInterface;
use App\Interfaces\EvaluacionInterface;






class PdfController extends Controller
{
    protected $UserRepository;
    protected $TareasRepository;
    protected $EvaluacionRepository;
    public function __construct(UserInterface $UserRepository, TareasInterface $TareasRepository, EvaluacionInterface $EvaluacionRepository)
    {

        $this->UserRepository = $UserRepository;
        $this->TareasRepository = $TareasRepository;
        $this->EvaluacionRepository = $EvaluacionRepository;

    }

    public function generarReporteEstudiante(request $request, $id, $id_m, $id_p)
    {

        if ($request['comentario'] != '') {
            $comentario = $request['comentario'];
        } else {
            $comentario = null;
        }

        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            $imagen = $request->file('imagen');

            $contenido = file_get_contents($imagen);

            $mime = $imagen->getClientMimeType();

            $firma = 'data:' . $mime . ';base64,' . base64_encode($contenido);
        } else {
            $firma = null;
        }
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



        $profesor = asignacion_profesor::join('users as u', 'asignacion_profesor.id_u', '=', 'u.id')
            ->select('u.usuario_nombres', 'u.usuario_app', 'u.usuario_apm')
            ->where('id_pm', $id_p)
            ->first();



        if (!empty($usuario->fotoperfil)) {
            $path = public_path($usuario->fotoperfil);

            if (file_exists($path) && is_readable($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $imgData = base64_encode(file_get_contents($path));
                $src = 'data:image/' . $type . ';base64,' . $imgData;
            } else {

                $src = null;
            }
        } else {

            $src = null;
        }


        $path_body = public_path('imagenes/imagenreporte.jpg');
        $type_body = pathinfo($path_body, PATHINFO_EXTENSION);
        $imgData_body = base64_encode(file_get_contents($path_body));
        $src_body = 'data:image/' . $type_body . ';base64,' . $imgData_body;

        $evaluacionesEstudiante = $estudiantesEvaluaciones['evaluaciones'];
        $tareasEstudiantes = $estudiantesTareas['tareas'];
        $data = [
            'usuario' => $usuario,
            'nombreMod' => $nombreMod,
            'paralelo' => $paralelo,

            'profesor' => $profesor,
            'materia' => $materia,
            'img_src' => $src,
            'src_body' => $src_body,
            'firma' => $firma
        ];
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);

        // Carga la vista y renderiza su contenido
        $html = view('Reportes.estudianteRep1', compact(
            'data',
            'comentario',
            'evaluacionesEstudiante',
            'tareasEstudiantes'
        ))->render();

        // Renderiza la vista HTML a PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait'); // También puedes usar 'landscape' para orientación horizontal


        // Renderiza el PDF (puede llevar tiempo si hay mucho contenido)
        $dompdf->render();

        $fecha_ = Carbon::now();
        // Devuelve el PDF generado para su descarga
        return $dompdf->stream('Reporte_' . $usuario->name . '_' . $fecha_ . '.pdf', array('Attachment' => 0));

    }



    public function reporte_horarios()
    {

        $path_body = public_path('imagenes/imagenreporte.jpg');
        $type_body = pathinfo($path_body, PATHINFO_EXTENSION);
        $imgData_body = base64_encode(file_get_contents($path_body));
        $src_body = 'data:image/' . $type_body . ';base64,' . $imgData_body;


        $userid = Auth::id();
        $usuario = User::find($userid);
        $horariosF = $this->UserRepository->getHorariosProfesor($userid);
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);

        $fecha = Carbon::now()->locale('es')->translatedFormat('j \d\e F \d\e Y');

        $html = view('Reportes.reporte_horarios', compact('horariosF', 'src_body', 'usuario', 'fecha'))->render();


        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');


        $fecha_ = Carbon::now();
        $dompdf->render();


        return $dompdf->stream('Reporte_horarios_' . $fecha_ . '.pdf', array('Attachment' => 0));

    }
}
