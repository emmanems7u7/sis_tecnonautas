<?php

namespace App\Http\Controllers;

use App\Interfaces\PreguntasInterface;
use App\Models\Preguntas;
use App\Models\Opcion;
use App\Models\PregPorEval;
use Illuminate\Http\Request;

class PreguntasController extends Controller
{

    protected $preguntasInterface;

    public function __construct(PreguntasInterface $preguntasInterface)
    {
        $this->preguntasInterface = $preguntasInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function list($id_e)
    {

        $preguntas = $this->preguntasInterface->list($id_e);

        return view('preguntas.lista_parcial', compact('preguntas'));
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
            $validated = $request->validate([
                'id_e' => 'required|integer|exists:evaluacions,id',
                'texto_pregunta' => 'required|string|max:255',
                'tipo_pregunta' => 'required|in:opciones,casillas,parrafo',
                'opciones' => [
                    'nullable',
                    'array',
                ],
            ], [
                'id_e.required' => 'El campo evaluación es obligatorio.',
                'id_e.integer' => 'El campo evaluación debe ser un número.',
                'id_e.exists' => 'La evaluación seleccionada no existe.',

                'texto_pregunta.required' => 'El texto de la pregunta es obligatorio.',
                'texto_pregunta.string' => 'El texto debe ser una cadena.',
                'texto_pregunta.max' => 'El texto no puede tener más de 255 caracteres.',

                'tipo_pregunta.required' => 'Debes seleccionar un tipo de pregunta.',
                'tipo_pregunta.in' => 'El tipo de pregunta debe ser válido.',

                'opciones.array' => 'Las opciones deben estar en formato de lista.',
            ]);

            // Validación adicional si el tipo requiere opciones
            if (in_array($validated['tipo_pregunta'], ['opciones', 'casillas'])) {
                if (isset($request->opciones)) {
                    foreach ($request->opciones as $i => $opcion) {
                        if (is_null($opcion) || trim($opcion) === '') {

                            return redirect()->back()->with('error', "Debe ingresar texto para las opciones ");

                        }

                    }
                } else {
                    return redirect()->back()->with('error', 'Debe agregar al menos una opción para este tipo de pregunta.');
                }

            }
            $this->preguntasInterface->storePreguntas($request);

            return redirect()->back()->with('status', 'Pregunta almacenada exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error inesperado: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Preguntas  $preguntas
     * @return \Illuminate\Http\Response
     */
    public function show(Preguntas $preguntas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Preguntas  $preguntas
     * @return \Illuminate\Http\Response
     */
    public function edit(Preguntas $preguntas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Preguntas  $preguntas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Preguntas $preguntas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Preguntas  $preguntas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $preguntas = Preguntas::findOrFail($id);

        $preguntas->delete();
        return redirect()->back()->with('success', 'Pregunta eliminada exitosamente.');
    }
}
