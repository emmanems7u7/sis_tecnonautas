<?php

namespace App\Http\Controllers;

use App\Models\admpago;
use App\Models\Modulo;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use App\Models\Estudiantes_asignacion_paramodulo;
use App\Models\paralelo_modulo;
use App\Models\TipoPago;
use App\Models\Pago;
use App\Models\Apoderado;
use App\Interfaces\CorreoInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use App\Interfaces\NotificationInterface;
use Illuminate\Support\Facades\Http;
use App\Models\AuditoriaPago;
use App\Models\ConfCorreo;
use App\Notifications\PagoNoAutomatizado;
use App\Notifications\PagoAprobado;


use PhpParser\Node\Stmt\Foreach_;
use Symfony\Component\Console\Output\NullOutput;
use App\Notifications\PagoRechazado;
class PagoController extends Controller
{
    public $userId;
    protected $NotificationRepository;
    protected $CorreoRepository;

    public function __construct(CorreoInterface $correoInterface, NotificationInterface $NotificationRepository)
    {
        $this->CorreoRepository = $correoInterface;
        $this->NotificationRepository = $NotificationRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Pagos', 'url' => route('pagos.index')],
        ];
        $materias = Estudiantes_asignacion_paramodulo::with(['asignacion', 'paraleloModulo.modulo'])->get();

        $materiasConModulos = [];

        foreach ($materias as $materia) {
            $pagado = admpago::where('id_apm', $materia->id)->first();

            $asignacion = $materia->asignacion->nombre;
            $costo = $materia->asignacion->costo;
            $userId = $materia->id_u;
            $dato_user = User::find($userId);
            $nombre_usuario = $dato_user->usuario_nombres . ' ' . $dato_user->usuario_app . ' ' . $dato_user->usuario_apm;
            $modulo = [
                'id_pago' => $pagado ? $pagado->id : null,
                'pagado' => $pagado ? $pagado->pagado : null,
                'modulo' => $materia->paraleloModulo->modulo->nombreM,
                'activo' => $materia->activo,
                'fecha_registro' => $materia->created_at
            ];

            // Buscar si el usuario ya existe en $materiasConModulos
            $userIndex = array_search($userId, array_column($materiasConModulos, 'user_id'));

            if ($userIndex !== false) {
                // Si el usuario ya existe, buscar la asignación dentro de ese usuario
                $asignacionIndex = array_search($asignacion, array_column($materiasConModulos[$userIndex]['asignaciones'], 'asignacion'));

                if ($asignacionIndex !== false) {
                    // Si la asignación ya existe, agregar el módulo
                    $materiasConModulos[$userIndex]['asignaciones'][$asignacionIndex]['modulos_inscritos'][] = $modulo;
                } else {
                    // Si la asignación no existe, agregar una nueva entrada para la asignación
                    $materiasConModulos[$userIndex]['asignaciones'][] = [
                        'asignacion' => $asignacion,
                        'costo' => $costo,
                        'modulos_inscritos' => [$modulo]
                    ];
                }
            } else {
                // Si el usuario no existe, crear un nuevo registro para él
                $materiasConModulos[] = [
                    'user_id' => $userId,
                    'nombre' => $nombre_usuario,
                    'asignaciones' => [
                        [
                            'asignacion' => $asignacion,
                            'costo' => $costo,
                            'modulos_inscritos' => [$modulo]
                        ]
                    ]
                ];
            }
        }

        $page = Paginator::resolveCurrentPage();
        $perPage = 5;
        $total = count($materiasConModulos);

        // Calcular el desplazamiento (offset) para la página actual
        $offset = ($page - 1) * $perPage;

        // Extraer los elementos correspondientes a la página actual
        $items = array_slice($materiasConModulos, $offset, $perPage);

        // Crear la instancia del Paginador
        $materiasConModulosPaginated = new Paginator($items, $total, $perPage, []); // El cuarto parámetro es un arreglo vacío

        // Establecer la ruta de los enlaces de paginación
        $materiasConModulosPaginated->withPath(Paginator::resolveCurrentPath());

        // dd($materiasConModulos);
        return view('pagos.index', compact('breadcrumb', 'materiasConModulosPaginated'));
    }
    public function PagoPendiente()
    {

        $user = $this->userId = Auth::id();

        $materias = Estudiantes_asignacion_paramodulo::where('id_u', $user)
            ->with(['asignacion', 'paraleloModulo.modulo'])
            ->get();


        //dd($materias);

        $materiaArray = [];

        foreach ($materias as $materia) {
            $pagado = admpago::find($materia->id);

            $asignacion = $materia->asignacion->nombre;
            $costo = $materia->asignacion->costo;

            $modulo = [
                'id_pago' => $pagado->id,
                'pagado' => isset($pagado) ? $pagado->pagado : null,
                'modulo' => $materia->paraleloModulo->modulo->nombreM,
                'activo' => $materia->activo,
                'fecha_registro' => $materia->created_at
            ];

            // Buscar si ya existe la asignatura en $materiaArray
            $index = array_search($asignacion, array_column($materiaArray, 'asignacion'));

            if ($index !== false) {
                // Si ya existe, agregar el módulo a la lista de módulos existentes
                $materiaArray[$index]['modulos_inscritos'][] = $modulo;
            } else {
                // Si no existe, crear un nuevo registro
                $materiaArray[] = [

                    'asignacion' => $asignacion,
                    'costo' => $costo,
                    'modulos_inscritos' => [$modulo]
                ];
            }
        }
        $materiasConModulos[] = $materiaArray;
        $tiposDePago = TipoPago::where('activo', 1)->get();

        $apoderados = Apoderado::where('id_u', $user)->get();

        $correo = ConfCorreo::first();

        // dd($apoderados);
        return view('pagos.pagoEstudiante', compact('correo', 'materiaArray', 'tiposDePago', 'apoderados'));
    }

    public function PagoPendiente_noti()
    {

        $user = $this->userId = Auth::id();

        $materias = Estudiantes_asignacion_paramodulo::where('id_u', $user)
            ->with(['asignacion', 'paraleloModulo.modulo'])
            ->get();

        $materiaArray = [];

        foreach ($materias as $materia) {
            $pagado = admpago::where('id_apm', $materia->id)->first();

            $asignacion = $materia->asignacion->nombre;
            $costo = $materia->asignacion->costo;

            $modulo = [
                'id_pago' => $pagado->id,
                'pagado' => isset($pagado) ? $pagado->pagado : null,
                'modulo' => $materia->paraleloModulo->modulo->nombreM,
                'activo' => $materia->activo,
                'fecha_registro' => $materia->created_at
            ];

            // Buscar si ya existe la asignatura en $materiaArray
            $index = array_search($asignacion, array_column($materiaArray, 'asignacion'));

            if ($index !== false) {
                // Si ya existe, agregar el módulo a la lista de módulos existentes
                $materiaArray[$index]['modulos_inscritos'][] = $modulo;
            } else {
                // Si no existe, crear un nuevo registro
                $materiaArray[] = [

                    'asignacion' => $asignacion,
                    'costo' => $costo,
                    'modulos_inscritos' => [$modulo]
                ];
            }
        }
        $materiasConModulos[] = $materiaArray;
        $tiposDePago = TipoPago::where('activo', 1)->get();

        $apoderados = Apoderado::where('id_u', $user)->get();

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Pagos pendientes', 'url' => route('users.index')],
        ];
        // dd($apoderados);
        return view('pagos.pagoEstudiante', compact('breadcrumb', 'materiaArray', 'tiposDePago', 'apoderados'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detalle($id)
    {
        $pago = admpago::find($id);
        $apm = Estudiantes_asignacion_paramodulo::find($pago->id_apm);

        $asignacion = Asignacion::find($apm->id_a);
        $paralelo_modulo = paralelo_modulo::find($apm->id_pm);
        $modulo = Modulo::find($paralelo_modulo->id_m);

        return json_encode([
            'asignacion' => $asignacion->nombre,
            'modulo' => $modulo->nombreM,
            'costo' => $asignacion->costo,
            'tipo' => $asignacion->tipo,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function datos_cuenta($cuenta)
    {

        $cuentas = Pago::where('id_tp', $cuenta)->get();

        foreach ($cuentas as $cuenta) {
            $cuenta->imagen = Storage::url($cuenta->imagen);
        }

        return json_encode($cuentas);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store_pago(request $request)
    {

        $user = Auth::user();

        $validated = $request->validate([
            'tipo_pago_s' => 'required|integer|exists:tipo_pagos,id',
            'id_a_s' => 'required|integer|exists:apoderados,id',
            'monto' => 'required|numeric|min:0',
            'numeroComprobante' => 'required|string|max:255|unique:admpagos,numeroComprobante',
            'fecha_pago' => 'required|date',
            'imagenComprobante' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|unique:admpagos,imagenComprobante',
            'pago_id' => 'required|integer',
        ]);

        if ($request->hasFile('imagenComprobante')) {
            $imagen = $request->file('imagenComprobante');


            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();

            $imagen->move(public_path('imagenes'), $nombreImagen);

            $imagenPath = 'imagenes/' . $nombreImagen;
        }

        $pago = admpago::find($request->pago_id);

        $pago->id_a = $request->id_a_s;
        $pago->metodo_pago = $request->tipo_pago_s;
        $pago->imagenComprobante = $imagenPath;

        $pago->monto = $request->monto;
        $pago->fecha_pago = $request->fecha_pago;
        $pago->numeroComprobante = $request->numeroComprobante;
        $pago->save();

        $pago_actualizado = $this->PagoAutomatico($pago->id, $user);

        //$pago_actualizado = $this->PagoAutomatico(9, $user);

        if ($pago_actualizado == 3) {
            return redirect()->back()->with('error', 'Multiples errores en su registro de pago, por favor verifique los datos e intente nuevamente');
        } elseif ($pago_actualizado == 1) {
            return redirect()->back()->with('status', 'Se registró el pago correctamente');
        } elseif ($pago_actualizado == 2) {

            return redirect()->back()->with('warning', 'Algo pasó durante el registro del pago, no te preocupes, ya notificamos a los administradores, ellos se encargarán de verificarlo y te notificaremos cuando esté listo');
        }


    }
    function PagoAutomatico($pago_id, $user)
    {

        $pago = admpago::find($pago_id);

        $fecha_pago = Carbon::parse($pago->fecha_pago)->format('Y-m-d');

        $fecha_fin = Carbon::parse($pago->fecha_pago)->addDays(7)->format('Y-m-d');

        $tipo_pago = Pago::where('id_tp', $pago->metodo_pago)->first();


        $email_apoderado = Apoderado::find($pago->id_a)->email;

        $asignacion_para_mod = Estudiantes_asignacion_paramodulo::find($pago->id_apm);

        $asignacion = Asignacion::find($asignacion_para_mod->id_a);

        $messages = $this->CorreoRepository->CorreoPagos($email_apoderado, $fecha_pago, $fecha_fin);

        // Validaciones
        $mensaje_valido = $this->validarCorreo($messages, $tipo_pago, $pago, $asignacion);

        if ($mensaje_valido['status'] == 'error') {
            $datos_correo = $mensaje_valido['contenido'];
        } else {
            $datos_correo = $mensaje_valido['contenido'];
        }

        $datos_imagen = $this->procesarImagen($pago->imagenComprobante);
        $imagen_valida = $this->validarImagen($datos_imagen, $pago, $tipo_pago, $asignacion);

        if ($imagen_valida['status'] == 'error') {
            $datos_imagen = [
                'error' => 'Error en los datos de la imagen',
                'detalle' => $imagen_valida['contenido']
            ];
        }

        $estatus = 'error';

        if (
            $mensaje_valido['status'] != 'error' &&
            $imagen_valida['status'] != 'error'
        ) {
            $estatus = 'validado';
            $pago->pagado = 1;
        } elseif (
            $mensaje_valido['status'] == 'error' &&
            $imagen_valida['status'] == 'error'
        ) {
            $estatus = 'error total';



            $pago->metodo_pago = null;
            $pago->imagenComprobante = null;
            $pago->pagado = 3;
            $pago->monto = null;
            $pago->fecha_pago = null;
            $pago->numeroComprobante = Null;

            $this->notificarAdministradores($pago_id, $user);
        } else {
            $estatus = 'error parcial';
            $pago->pagado = 2;
            $this->notificarAdministradores($pago_id, $user);
        }


        $pago->save();

        AuditoriaPago::create([
            'id_pago' => $pago->id,
            'estatus' => $estatus,
            'data_correo' => $datos_correo,
            'data_imagen' => $datos_imagen,
        ]);


        if ($pago->pagado == 1) {
            $asignacion_para_mod->activo = 'activo';
            $asignacion_para_mod->save();
        }
        return $pago->pagado;
    }

    private function validarCorreo($messages, $tipo_pago, $pago, $asignacion)
    {

        $errores = [];

        if (!empty($messages)) {
            $message = '';
            foreach ($messages as $message) {
                if ($tipo_pago->email != $message['remitente']) {
                    $errores[] = 'El remitente del correo no coincide.';
                }

                if ($pago->numeroComprobante != $message['numTransaccion']) {
                    $errores[] = 'El número de comprobante no coincide.';
                }

                if ($asignacion->costo != $message['montoTransferido']) {
                    $errores[] = 'El monto transferido no coincide con el costo de la asignación.';
                }

                if ($tipo_pago->numero_cuenta != $message['destinoCuenta']) {
                    $errores[] = 'La cuenta de destino no coincide.';
                }

                if (
                    $tipo_pago->email == $message['remitente'] &&
                    $pago->numeroComprobante == $message['numTransaccion'] &&
                    $asignacion->costo == $message['montoTransferido'] &&
                    $tipo_pago->numero_cuenta == $message['destinoCuenta']
                ) {

                    return [
                        'status' => 'success',
                        'error' => '',
                        'contenido' => $message,
                    ];

                }
            }
        }

        return [
            'status' => 'error',
            'error' => 'No se encontró un correo que coincida con los datos del pago.',
            'contenido' =>
                [
                    'errores' => $errores,
                    'datos_correo' => 'No se encontró un correo que coincida con los datos del pago.',
                ],
        ];
    }

    private function procesarImagen($imagenComprobante)
    {
        $imagePath = public_path($imagenComprobante);
        $texto = $this->valida_imagen($imagePath);

        // dd($texto['data']);
        return $texto['data'] ?? null;
    }

    private function validarImagen($datos, $pago, $tipo_pago, $asignacion)
    {
        $errores = [];

        if (!$datos) {
            $errores[] = 'No se extrajo información de la imagen.';

            return [
                'status' => 'error',
                'error' => '',
                'contenido' => $errores,
            ];
        }

        if (
            is_null($datos['nro_transaccion']) ||
            is_null($datos['fecha']) ||
            is_null($datos['total_enviado']) ||
            is_null($datos['numero_beneficiario'])
        ) {
            $errores[] = 'Todos los campos del comprobante deben estar presentes.';

            return [
                'status' => 'error',
                'error' => '',
                'contenido' => $errores,
            ];

        }
        if ($pago->numeroComprobante != $datos['nro_transaccion']) {
            $errores[] = 'El número de transacción de la imagen no coincide.';
        }

        if ($pago->fecha_pago != $datos['fecha']) {
            $errores[] = 'La fecha del comprobante no coincide.';
        }

        if ($asignacion->costo != $datos['total_enviado']) {
            $errores[] = 'El monto enviado desde la imagen no coincide con el costo.';
        }

        if ($tipo_pago->numero_cuenta != $datos['numero_beneficiario']) {
            $errores[] = 'El número de cuenta del beneficiario no coincide.';
        }

        if (empty($errores)) {

            return [
                'status' => 'success',
                'error' => '',
                'contenido' => '',
            ];

        }
        return [
            'status' => 'error',
            'error' => 'No se encontraon datos que coincidan con los datos del pago.',
            'contenido' =>
                [
                    'errores' => $errores,
                    'datos_imagen' => $datos,
                ],
        ];

    }

    private function notificarAdministradores($pago_id, User $user)
    {
        $adminUsers = User::role('admin')->get();
        foreach ($adminUsers as $adminUser) {
            $adminUser->notify(new PagoNoAutomatizado($pago_id, $user));
        }
    }

    function valida_imagen($imagePath)
    {
        if (!file_exists($imagePath)) {
            return null;

        }

        $base64Image = base64_encode(file_get_contents($imagePath));
        $apiKey = env('OCR_SPACE_API_KEY');
        $response = Http::asForm()->post('https://api.ocr.space/parse/image', [
            'apikey' => $apiKey,
            'base64Image' => 'data:image/jpeg;base64,' . $base64Image,
            'language' => 'spa'
        ]);
        $data = $response->json();

        if ($data['IsErroredOnProcessing']) {

            return null;

        }

        // Extraer el texto procesado
        $texto = $data['ParsedResults'][0]['ParsedText'] ?? 0;


        if ($texto != 0) {
            $lineas = array_values(array_filter(array_map('trim', explode("\n", $texto))));
            $resultado = [
                'fecha' => '',
                'total_enviado' => '',
                'nombre_beneficiario' => '',
                'numero_beneficiario' => '',
                'destino' => '',
                'envio_realizado_de' => '',
                'nro_transaccion' => '',
            ];
            for ($i = 0; $i < count($lineas); $i++) {
                if (preg_match('/\d{1,2}\s+\w+\.?\s+\d{4}/', $lineas[$i])) {
                    $resultado['fecha'] = $lineas[$i];
                }

                if (stripos($lineas[$i], 'Total enviado') !== false && isset($lineas[$i + 1])) {
                    $resultado['total_enviado'] = $lineas[$i + 1];
                    $resultado['nombre_beneficiario'] = $lineas[$i + 2] ?? '';
                    $resultado['numero_beneficiario'] = isset($lineas[$i + 3])
                        ? preg_replace('/\s+/', '', $lineas[$i + 3])
                        : '';
                }

                if (stripos($lineas[$i], 'Destino') !== false && isset($lineas[$i + 1])) {
                    $resultado['destino'] = $lineas[$i + 1];
                }

                if (stripos($lineas[$i], 'Envío realizado de') !== false && isset($lineas[$i + 1])) {
                    $resultado['envio_realizado_de'] = $lineas[$i + 1];
                }

                if (stripos($lineas[$i], 'Nro. de transacción') !== false && isset($lineas[$i + 1])) {
                    $resultado['nro_transaccion'] = $lineas[$i + 1];
                }
            }

            $fechaTexto = $resultado['fecha'] ?? '';

            $fechaFormateada = null;

            if (preg_match('/(\d{1,2})\s+(\w+)\.?\s+(\d{4})/', $fechaTexto, $partes)) {
                $dia = str_pad($partes[1], 2, '0', STR_PAD_LEFT);
                $mesTexto = strtolower($partes[2]);
                $anio = $partes[3];

                // Mapeo de meses
                $meses = [
                    'ene' => '01',
                    'feb' => '02',
                    'mar' => '03',
                    'abr' => '04',
                    'may' => '05',
                    'jun' => '06',
                    'jul' => '07',
                    'ago' => '08',
                    'sep' => '09',
                    'oct' => '10',
                    'nov' => '11',
                    'dic' => '12',

                    'ene.' => '01',
                    'feb.' => '02',
                    'mar.' => '03',
                    'abr.' => '04',
                    'may.' => '05',
                    'jun.' => '06',
                    'jul.' => '07',
                    'ago.' => '08',
                    'sep.' => '09',
                    'oct.' => '10',
                    'nov.' => '11',
                    'dic.' => '12',
                ];

                $mes = $meses[$mesTexto] ?? '01';

                $fechaFormateada = "$anio-$mes-$dia";
            } else {
                $fechaFormateada = null;
            }

            $totalTexto = $resultado['total_enviado'] ?? '';
            $totalEntero = null;

            if (preg_match('/\d+/', $totalTexto, $match)) {
                $totalEntero = (int) $match[0];
            }


            $resultado['fecha'] = $fechaFormateada;
            $resultado['total_enviado'] = $totalEntero;


            return ['status' => 'error', 'data' => $resultado];

        } else {
            return null;
        }

    }
    function auditoria($id)
    {

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Pagos', 'url' => route('pagos.index')],
            ['name' => 'Auditoría de pagos', 'url' => route('auditoria.index', $id)],
        ];
        $search = '';
        $pagos = admpago::where('id', $id)->with('transacciones')->get();
        foreach ($pagos as &$pago) {

            $pago->metodo_pago = TipoPago::find($pago->metodo_pago)->nombre ?? null;

            $paramod = Estudiantes_asignacion_paramodulo::find($pago->id_apm);
            if ($paramod) {

                $estudiante = User::find($paramod->id_u);
                if ($estudiante) {
                    $pago->nombre_estudiante = $estudiante->usuario_nombres . ' ' . $estudiante->usuario_app . ' ' . $estudiante->usuario_apm;

                }
            }

        }

        return view('pagos.auditoria', compact('breadcrumb', 'search', 'pagos'));
    }
    function pago_rechazo($id)
    {
        $pago = admpago::find($id);

        $pago->id_a = null;
        $pago->metodo_pago = null;
        $pago->imagenComprobante = null;
        $pago->monto = null;
        $pago->fecha_pago = null;
        $pago->pagado = 3;
        $pago->numeroComprobante = null;
        $pago->save();

        $apm = Estudiantes_asignacion_paramodulo::find($pago->id_apm);
        $apm->activo = 'inactivo';
        $apm->save();

        $user = User::find($apm->id_u);
        $user->notify(new PagoRechazado());

        return redirect()->back()->with('status', 'Se rechazó el pago correctamente');
    }

    function pago_aprobar($id)
    {
        $pago = admpago::find($id);


        $pago->pagado = 1;
        $pago->save();

        $apm = Estudiantes_asignacion_paramodulo::find($pago->id_apm);
        $apm->activo = 'activo';
        $apm->save();

        $user = User::find($apm->id_u);
        $user->notify(new PagoAprobado($user));

        return redirect()->back()->with('status', 'Se aprobó el pago correctamente');
    }

}
