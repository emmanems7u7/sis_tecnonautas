<?php
namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\paralelo_modulo;
use App\Models\horario;
use App\Models\asigModulo;
use App\Models\asignacion_profesor;
use Carbon\Carbon;
use App\Models\Modulo;

class UserRepository extends BaseRepository implements UserInterface
{
    public function __construct()
    {
        parent::__construct();

    }
    public function CrearUsuario($request)
    {
        $this->validar_datos($request);

        $user = User::create(attributes: [
            'name' => $this->cleanHtml($request->input('name')),
            'email' => $this->cleanHtml($request->input('email')),
            'password' => Hash::make($this->configuracion->conf_defecto),
            'usuario_fecha_ultimo_acceso' => null,
            'usuario_fecha_ultimo_password' => null,
            'usuario_nombres' => $this->cleanHtml($request->input('usuario_nombres')),
            'usuario_app' => $this->cleanHtml($request->input('usuario_app')),
            'usuario_apm' => $this->cleanHtml($request->input('usuario_apm')),
            'usuario_telefono' => $this->cleanHtml($request->input('usuario_telefono')),
            'usuario_direccion' => $this->cleanHtml($request->input('usuario_direccion')),
            'accion_fecha' => now(),
            'accion_usuario' => Auth::user()->name,
            'usuario_activo' => 1,
        ]);
        return $user;
    }
    public function EditarUsuario($request, $id, $perfil)
    {

        $this->validar_datos($request, $id, $perfil);
        $user = User::findOrFail($id);
        if ($perfil == 1) {

            $request->validate([
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($request->hasFile('profile_picture')) {


                $file = $request->file('profile_picture');


                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();


                $destinationPath = public_path('imagenes/fotos_perfiles');

                // verifica si carpeta existe, si no la crea
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0775, true);
                }


                $file->move($destinationPath, $fileName);
                $foto_perfil = 'imagenes/fotos_perfiles/' . $fileName;
            } else {
                $foto_perfil = $user->fotoperfil;

            }

        } else {
            $foto_perfil = $user->fotoperfil;
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'usuario_nombres' => $request->input('usuario_nombres'),
            'usuario_app' => $request->input('usuario_app'),
            'usuario_apm' => $request->input('usuario_apm'),
            'usuario_telefono' => $request->input('usuario_telefono'),
            'usuario_direccion' => $request->input('usuario_direccion'),
            'accion_fecha' => now(),
            'accion_usuario' => Auth::user()->name,
            'usuario_activo' => 1,
            'fotoperfil' => $foto_perfil,
        ]);
        return $user;
    }

    public function EditarDatosPersonales($request, $id)
    {
        $user = User::findOrFail($id);

        $this->validar_datos($request, $id);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'usuario_nombres' => $request->input('usuario_nombres'),
            'usuario_app' => $request->input('usuario_app'),
            'usuario_apm' => $request->input('usuario_apm'),
            'usuario_telefono' => $request->input('usuario_telefono'),
            'usuario_direccion' => $request->input('usuario_direccion'),

            'accion_fecha' => now(),
            'accion_usuario' => Auth::user()->name,
            'usuario_activo' => 1,
        ]);
    }

    public function GetUsuario($id)
    {
        $user = User::find($id);

        if ($user) {

            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
                'usuario_nombres' => $user->usuario_nombres,
                'usuario_app' => $user->usuario_app,
                'usuario_apm' => $user->usuario_apm,
                'usuario_telefono' => $user->usuario_telefono,
                'usuario_direccion' => $user->usuario_direccion,
            ]);
        } else {

            return response()->json(['error' => 'Datos no encontrados'], 404);
        }
    }
    public function GetUsuarios()
    {

    }

    function validar_datos($request, $user_id = null, $perfil = 0)
    {
        $email_validacion = 'required|email|not_regex:/<\s*script/i';

        if ($user_id) {
            $email_validacion .= '|unique:users,email,' . $user_id;
        } else {
            $email_validacion .= '|unique:users,email';
        }


        $validated = $request->validate([
            'name' => 'required|string|max:255|not_regex:/<\s*script/i',
            'email' => $email_validacion,
            'usuario_nombres' => 'required|string|max:100|not_regex:/<\s*script/i',
            'usuario_app' => 'required|string|max:50|not_regex:/<\s*script/i',
            'usuario_apm' => 'required|string|max:50|not_regex:/<\s*script/i',
            'usuario_telefono' => 'required|regex:/^[1-9][0-9]*$/',
            'usuario_direccion' => 'required|string|max:1000|not_regex:/<\s*script/i',

        ]);

        if ($perfil == 0) {
            $validated = $request->validate([
                'role' => 'required|exists:roles,name',
            ]);
        }
    }

    public function getEstudiantes()
    {

    }

    public function getEstudiante($id)
    {

    }


    public function getProfesores()
    {
        $rolP = Role::where('name', 'profesor')->first();
        return $rolP->users()->get();
    }

    public function getProfesor($id)
    {

    }
    public function getProfesorParalelo($id_pm)
    {
        return User::select('users.id', 'users.usuario_nombres', 'users.usuario_app', 'users.usuario_apm')
            ->join('asignacion_profesor', 'users.id', '=', 'asignacion_profesor.id_u')
            ->join('paralelo_modulos', 'asignacion_profesor.id_pm', '=', 'paralelo_modulos.id')
            ->where('paralelo_modulos.id', '=', $id_pm)
            ->first();
    }


    public function getAdministradores()
    {

    }
    public function getAdministrador($id)
    {

    }

    public function getHorariosProfesor($userid)
    {

        $datosP = asignacion_profesor::where('id_u', $userid)->get();

        if ($datosP->isEmpty()) {
            return 0;
        }
        foreach ($datosP as $dat) {

            $paramod = paralelo_modulo::where('id', $dat->id_pm)->pluck('id_m')->first();

            $materia = asigModulo::join('asignacions', 'asig_modulos.id_a', '=', 'asignacions.id')
                ->where('asig_modulos.id_m', $paramod)
                ->select('asignacions.nombre')
                ->pluck('asignacions.nombre')
                ->first();

            $paralelo_modulo = paralelo_modulo::find(id: $dat->id_pm);
            $modulo = Modulo::find($paralelo_modulo->id_m);
            $horarios = horario::where('id_mp', $dat->id_pm)->get();
            foreach ($horarios as $horario) {
                $horario->inicio = Carbon::parse($horario->inicio)->format('H:i');
                $horario->fin = Carbon::parse($horario->fin)->format('H:i');

            }

            $horariosF[] = [

                'materia' => $materia . ' (' . $modulo->nombreM . ')',
                'horarios' => $horarios,

            ];
        }

        return $horariosF;
    }

}
