<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoPago;

class TipoPagoController extends Controller
{


    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255|unique:tipo_pagos,nombre',
            ]);

            TipoPago::create([
                'nombre' => $request->nombre,
                'activo' => $request->has('activo'),
            ]);

            return redirect()->back()->with('status', 'Tipo de pago registrado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errores = $e->validator->errors()->all();
            return redirect()->back()
                ->withErrors($e->errors())
                ->with('error', 'Error de validación: ' . implode(', ', $errores));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Intente nuevamente.');
        }
    }
    public function destroy($id)
    {
        try {
            $tipoPago = TipoPago::findOrFail($id); // Buscar el tipo de pago
            $tipoPago->delete(); // Eliminar el registro


            return redirect()->back()->with('status', 'Tipo de pago eliminado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el tipo de pago.');

        }
    }
    function estado($id)
    {
        $tipoPago = TipoPago::find($id);

        if ($tipoPago->activo == 1) {
            $tipoPago->activo = 0;
        } else {
            $tipoPago->activo = 1;
        }
        $tipoPago->save();
        return redirect()->back()->with('status', 'Estado Actualizado Correctamente.');


    }

}
