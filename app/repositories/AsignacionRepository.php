<?php

namespace App\Repositories;
use App\Interfaces\ParalelosInterface;
use App\Interfaces\UserInterface;
use App\Models\Asignacion;
use App\Interfaces\AsignacionInterface;
use App\Models\Modulo;
use App\Models\paralelo_modulo;
use App\Models\Caracteristica;
use App\Models\Objetivo;
use App\Models\Beneficio;
use App\Models\admpago;
use App\Models\Paralelo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Models\User;
use App\Notifications\HabilitarEstudiante;
use App\Notifications\RealizarPago;

use App\Traits\Base64ToFile;
use Illuminate\Support\Facades\Auth;

use App\Models\Estudiantes_asignacion_paramodulo;
use App\Notifications\ParaleloModuloCupo;

class AsignacionRepository implements AsignacionInterface
{

    protected $ParaleloRepository, $UserRepository;

    public function __construct(ParalelosInterface $ParaleloRepository, UserInterface $UserRepository)
    {
        $this->ParaleloRepository = $ParaleloRepository;

        $this->UserRepository = $UserRepository;
    }
    public function InscripcionPago($userId, $request)
    {
        $adminUsers = User::role('admin')->get();
        $asignacion = Asignacion::find($request->query('id_a'));
        $paraMod = paralelo_modulo::find($request->query('id_pm'));
        $modulo_nombre = Modulo::find($paraMod->id_m);
        $paralelo = Paralelo::find($paraMod->id_p);
        try {

            try {

                $cupo_paralelo = Paralelo::find($paraMod->id_p)->cupo;
                //if (false) {
                if ($paraMod->inscritos <= $cupo_paralelo) {

                    $asig = Estudiantes_asignacion_paramodulo::create([

                        'id_u' => $userId,
                        'id_a' => $request->query('id_a'),
                        'id_pm' => $request->query('id_pm'),
                        'activo' => 'inactivo',
                        'nota' => 0

                    ]);

                    $userN = User::find($userId);



                    foreach ($adminUsers as $adminUser) {
                        $adminUser->notify(new HabilitarEstudiante($userId));
                    }

                    $paraMod->inscritos = $paraMod->inscritos + 1;
                    $paraMod->save();
                    if ($paraMod->inscritos == $cupo_paralelo) {
                        foreach ($adminUsers as $adminUser) {
                            $adminUser->notify(new ParaleloModuloCupo($asignacion, $modulo_nombre, $paralelo));
                        }
                    }

                    $this->RegistrarPago($asig->id, $userN);

                } else {


                    foreach ($adminUsers as $adminUser) {
                        $adminUser->notify(new ParaleloModuloCupo($asignacion, $modulo_nombre, $paralelo));
                    }
                    return redirect()->back()->with('error', 'El paralelo ya no tiene cupo disponible');


                }


            } catch (\Exception $e) {

                return redirect()->back()->with('error', 'Error al guardar Asignacion' . $e->getMessage());
            }

            try {

            } catch (\Exception $e) {

                return redirect()->back()->with('error', 'Error al guardar el pago' . $e->getMessage());
            }

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Error al guardar varios Datos' . $e->getMessage());
        }


    }
    function RegistrarPago($asignacion_id, $userN)
    {
        $pago = admpago::create([
            'id_apm' => $asignacion_id,
            'id_a' => null,
            'pagado' => 0,
            'metodo_pago' => null,
            'monto' => null,
            'fecha_pago' => null,
            'imagenComprobante' => null,
            'numeroComprobante' => null,
        ]);
        $userN->notify(new RealizarPago());
    }
    public function InscripcionGratuito($userId, $request)
    {

    }
    public function GetInscripcion($request)
    {
        $idMateria = $request->query('id_a');
        $nombreModulo = 'Modulo 1';
        $asignacion = Asignacion::select('asignacions.id', 'asignacions.nombre', 'asignacions.descripcionCorta', 'asignacions.imagen1', 'asignacions.tipo', 'asignacions.costo')
            ->where('asignacions.id', '=', $idMateria)
            ->first();

        $datosModulo = Modulo::select('modulos.id', 'nombreM', 'Duracion', 'Descripcion')
            ->join('asig_modulos', 'modulos.id', '=', 'asig_modulos.id_m')
            ->where('nombreM', $nombreModulo)
            ->where('asig_modulos.id_a', $idMateria)
            ->first();

        $datosParalelo = $this->ParaleloRepository->GetDatosParalelosID(paralelo_modulo::where('id_m', $datosModulo->id)->get());


        $id = $asignacion->id;
        $nombre = $asignacion->nombre;
        $descripcion = $asignacion->descripcionCorta;
        $costo = $asignacion->costo;
        $imagen = $asignacion->imagen1;
        //datos modulo 1 de la materia
        if ($datosModulo) {
            $nombreM = $datosModulo->nombreM;
            $duracion = $datosModulo->Duracion;
            $descripcionMod = $datosModulo->Descripcion;
        } else {
            return response()->json(['error' => 'Módulo no encontrado'], 404);
        }

        $data = [
            'id' => $id,
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'imagen' => $imagen,
            'precio' => $costo,
            'datosParalelo' => $datosParalelo,

            'datosModulo' => $datosModulo,
            'nombreM' => $nombreM,
            'duracion' => $duracion,
            'descripcionMod' => $descripcionMod,

        ];

        return $data;
    }
    public function GetAsignaciones()
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole('profesor')) {
            $e = DB::table('asignacions')->get();
        } else {
            $e = DB::table('estudiantes_asignacion_paramodulos')
                ->join('asignacions', 'estudiantes_asignacion_paramodulos.id_a', '=', 'asignacions.id')
                ->select('asignacions.id', 'asignacions.nombre', 'asignacions.descripcion', 'asignacions.imagen1')
                ->where('estudiantes_asignacion_paramodulos.id_u', Auth::id())
                ->groupBy('asignacions.id', 'asignacions.nombre', 'asignacions.descripcion', 'asignacions.imagen1')
                ->get();



        }
        return $e;
    }

    public function GetAsignacion($id_a)
    {

        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole(roles: 'profesor')) {
            /*$e = DB::table('estudiantes_asignacion_paramodulos')
            ->join('asignacions', 'estudiantes_asignacion_paramodulos.id_a', '=', 'asignacions.id')
            ->select('asignacions.id','asignacions.nombre', 'asignacions.descripcion', 'asignacions.imagen1')
            ->where('estudiantes_asignacion_paramodulos.id_a', $id_a)
            ->first();
            */
            $e = Asignacion::find($id_a);
        } else {
            $e = DB::table('estudiantes_asignacion_paramodulos')
                ->join('asignacions', 'estudiantes_asignacion_paramodulos.id_a', '=', 'asignacions.id')
                ->select('asignacions.id', 'asignacions.nombre', 'asignacions.descripcion', 'asignacions.imagen1')
                ->where('estudiantes_asignacion_paramodulos.id_u', Auth::id())
                ->where('estudiantes_asignacion_paramodulos.id_a', $id_a)
                ->first();


        }
        return $e;
    }

    public function GetAsignacionPagos()
    {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->hasRole('profesor')) {
            return Asignacion::where('tipo', 'pago')->get();
        } else {
            if ($user->hasRole('estudiante')) {
                $valores = Estudiantes_asignacion_paramodulo::where('id_u', $user->id)->get();
                $ids_a_excluir = $valores->pluck('id_a')->toArray();

                return Asignacion::whereNotIn('id', $ids_a_excluir)->get();


            }
        }

    }
    public function GetAsignacionGratuitos()
    {
        return Asignacion::where('tipo', 'gratuito')->get();
    }
    public function GuardarAsignacion($request)
    {

        if ($request->hasFile('portada_imagen') && $request->file('portada_imagen')->isValid()) {
            $file = $request->file('portada_imagen');
            $filename = uniqid('portada_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('imagenes'), $filename);
            $photoPath = 'imagenes/' . $filename;
        }

        if ($request->hasFile('img1')) {
            $file = $request->file('img1');
            $filename = uniqid('img1_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('imagenes'), $filename);
            $imagen1Path = 'imagenes/' . $filename;
        }


        $asig = Asignacion::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'descripcionCorta' => $request->input('descripcionCorta'),
            'tipo' => $request->input('tipo'),
            'imagen1' => $request->has('img1') ? $imagen1Path : null,
            'portada' => $request->has('portada_imagen') ? $photoPath : null,

            'costo' => $request->input('tipo') == 'pago' ? $request->input('costo', 0) : 0,
        ]);

        foreach ($request->input('caracteristicas') as $caracteristica) {
            $desc = Caracteristica::create([
                'id_a' => $asig->id,
                'caracteristica' => $caracteristica,
            ]);
        }
        foreach ($request->input('objetivos') as $objetivo) {
            $obj = Objetivo::create([
                'id_a' => $asig->id,
                'objetivo' => $objetivo,
            ]);
        }
        foreach ($request->input('beneficios') as $beneficio) {
            $benef = Beneficio::create([
                'id_a' => $asig->id,
                'beneficio' => $beneficio,
            ]);
        }


    }
}