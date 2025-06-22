<?php

namespace App\Http\Controllers;

use App\Models\CuentaImap;
use Illuminate\Http\Request;

class CuentaImapController extends Controller
{
    public function index()
    {
        $cuenta = CuentaImap::first();

        if ($cuenta) {
            return redirect()->route('cuentas.edit', $cuenta->id);
        }

        return redirect()->route('cuentas.create');
    }

    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Configuración IMAP', 'url' => route('cuentas.index')],
            ['name' => 'Crear', 'url' => ''],
        ];
        return view('cuentas_imap.create', compact('breadcrumbs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'nullable|string|max:100',
            'host' => 'required|string',
            'port' => 'required|integer',
            'encryption' => 'nullable|in:ssl,tls',
            'validate_cert' => 'boolean',
            'username' => 'required|email',
            'password' => 'required|string',
        ]);

        CuentaImap::create($data);

        return redirect()->route('cuentas.index')->with('success', 'Configuración IMAP creada correctamente.');
    }

    public function edit($id)
    {
        $cuenta = CuentaImap::findOrFail($id);
        $breadcrumbs = [
            ['name' => 'Configuración IMAP', 'url' => route('cuentas.index')],
            ['name' => 'Editar', 'url' => ''],
        ];
        return view('cuentas_imap.edit', compact('cuenta', 'breadcrumbs'));
    }

    public function update(Request $request, )
    {
        $cuenta = CuentaImap::first();

        $data = $request->validate([
            'nombre' => 'nullable|string|max:100',
            'host' => 'required|string',
            'port' => 'required|integer',
            'encryption' => 'nullable|in:ssl,tls',
            'validate_cert' => 'boolean',
            'username' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($cuenta) {
            $cuenta->update($data);
        } else {
            CuentaImap::create($data);
        }


        return redirect()->back()->with('status', 'Configuración IMAP actualizada correctamente.');
    }

    public function destroy($id)
    {
        // Opcional: solo si querés permitir eliminar
        CuentaImap::findOrFail($id)->delete();
        return redirect()->route('cuentas.index')->with('success', 'Configuración IMAP eliminada.');
    }
}

