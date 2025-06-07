<?php

namespace App\Http\Controllers;

use App\Models\metodosPago;
use Illuminate\Http\Request;
use App\Models\Pago;
use App\Traits\Base64ToFile;

class MetodosPagoController extends Controller
{
    public $var;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $metodosPago = Pago::all();
        return response()->json($metodosPago);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {

            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $nombre = time() . '_' . $archivo->getClientOriginalName();
                $destino = public_path('imagenes');

                if (!file_exists($destino)) {
                    mkdir($destino, 0755, true);
                }

                $archivo->move($destino, $nombre);


                $ruta = 'imagenes/' . $nombre;
            } else {
                $ruta = null;
            }

            // Crear el pago
            $Pago = Pago::create([
                'id_tp' => $request['tipoPagoSeleccionado'],
                'detalle' => $request['detalle'],
                'numero_cuenta' => $request['num_cuenta'],
                'banco' => $request['banco'],
                'imagen' => $ruta,
                'email' => $request['email'],
            ]);

            // Retorna una respuesta exitosa con el código 201 (Creado)
            return response()->json([
                'status' => 'success',
                'message' => 'Pago creado exitosamente',
                'data' => $Pago
            ], 201); // Código HTTP 201 para creación exitosa
        } catch (\Exception $e) {
            // Si ocurre un error inesperado, se captura la excepción y se retorna un error con el código 500
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear el pago',
                'error' => $e->getMessage()
            ], 500); // Código HTTP 500 para error interno del servidor
        }

        return response()->json(['status' => 'success', 'message' => 'Formulario enviado exitosamente']);

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\metodosPago  $metodosPago
     * @return \Illuminate\Http\Response
     */
    public function show(Pago $metodosPago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\metodosPago  $metodosPago
     * @return \Illuminate\Http\Response
     */
    public function edit(Pago $metodosPago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\metodosPago  $metodosPago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pago $metodosPago)
    {
        $Pago = Pago::findOrFail($request['id']);
        $Pago->update($request->all());
        return response()->json($Pago);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\metodosPago  $metodosPago
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pago $metodosPago)
    {
        //
    }
}
