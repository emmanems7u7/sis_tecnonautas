<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ConfiguracionCredenciales;

class CheckPasswordAge
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Evitar errores si no hay usuario
        if (!$user) {
            return redirect()->route('login');
        }

        $config = ConfiguracionCredenciales::first();
        $ultimoCambio = $user->usuario_fecha_ultimo_password;

        if (!$ultimoCambio || Carbon::parse($ultimoCambio)->diffInDays(Carbon::now()) >= $config->conf_duracion_max) {
            // Si no tiene fecha de cambio o ya venció, redirigir a cambio de contraseña
            return redirect()->route('user.actualizar.contraseña');
        }

        return $next($request);
    }
}
