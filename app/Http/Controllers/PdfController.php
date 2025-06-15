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


class PdfController extends Controller
{
    protected $UserRepository;
    public function __construct(UserInterface $UserRepository)
    {

        $this->UserRepository = $UserRepository;

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
        $evaluaciones = evaluacionCompleta::join('evaluacions as e', 'evaluacion_completas.id_e', '=', 'e.id')
            ->select(
                'e.id',
                'e.nombre',
                'e.detalle',
                \DB::raw('CONCAT(DAY(e.creado), " de ", CASE MONTH(e.creado)
            WHEN 1 THEN "Enero"
            WHEN 2 THEN "Febrero"
            WHEN 3 THEN "Marzo"
            WHEN 4 THEN "Abril"
            WHEN 5 THEN "Mayo"
            WHEN 6 THEN "Junio"
            WHEN 7 THEN "Julio"
            WHEN 8 THEN "Agosto"
            WHEN 9 THEN "Septiembre"
            WHEN 10 THEN "Octubre"
            WHEN 11 THEN "Noviembre"
            WHEN 12 THEN "Diciembre"
            END) AS creado'),
                \DB::raw('CONCAT(DAY(e.limite), " de ", CASE MONTH(e.limite)
            WHEN 1 THEN "Enero"
            WHEN 2 THEN "Febrero"
            WHEN 3 THEN "Marzo"
            WHEN 4 THEN "Abril"
            WHEN 5 THEN "Mayo"
            WHEN 6 THEN "Junio"
            WHEN 7 THEN "Julio"
            WHEN 8 THEN "Agosto"
            WHEN 9 THEN "Septiembre"
            WHEN 10 THEN "Octubre"
            WHEN 11 THEN "Noviembre"
            WHEN 12 THEN "Diciembre"
            END) AS limite'),
                'evaluacion_completas.completado',
                'evaluacion_completas.nota'
            )
            ->where('evaluacion_completas.id_u', $id)
            ->get();

        $tareasEstudiantes = DB::table('tareas_estudiantes as te')
            ->join('tareas as t', 'te.tareas_id', '=', 't.id')
            ->select('t.id', 't.nombre', 't.detalle', 't.limite', 'te.nota', 'te.created_at as entregado')
            ->where('te.user_id', $id)
            ->get();


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

        $data = [
            'usuario' => $usuario,
            'nombreMod' => $nombreMod,
            'paralelo' => $paralelo,
            'evaluaciones' => $evaluaciones,
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
        $html = view('Reportes.estudianteRep1', compact('data', 'tareasEstudiantes', 'comentario'))->render();

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

        $path_body = public_path('storage/imagenes/imagenreporte.jpg');
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
