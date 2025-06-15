<?php

namespace App\Http\Controllers;
use App\Models\Configuracion;
use Illuminate\Http\Request;

use App\Models\TipoPago;
use App\Models\VerificaRegistro;


class ConfiguracionController extends Controller
{
    public function index()
    {
        $tiposDePago = TipoPago::all();

        return view('configuracion.index', compact('tiposDePago'));

    }

    public function edit()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Configuracion', 'url' => route('admin.configuracion.edit')],
        ];
        $config = Configuracion::first();
        $tiposDePago = TipoPago::all();
        $registro = VerificaRegistro::first();

        return view('configuracion.configuracion_general', compact('registro', 'tiposDePago', 'config', 'breadcrumb'));
    }

    public function update(Request $request)
    {

        $config = Configuracion::first();

        $config->update([
            'doble_factor_autenticacion' => $request->has('doble_factor_autenticacion'),
            'limite_de_sesiones' => $request->input('limite_de_sesiones'),
            'GROQ_API_KEY' => $request->input('GROQ_API_KEY'),
            'mantenimiento' => $request->has('mantenimiento'),
        ]);

        return redirect()->back()->with('status', 'Configuración actualizada.');
    }
}
