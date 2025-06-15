<?php

namespace App\Http\Controllers;

use App\Models\Apoderado;
use App\Models\Celular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\NotificationInterface;
class ApoderadoController extends Controller
{
    protected $NotificationRepository;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(NotificationInterface $NotificationRepository)
    {
        $this->NotificationRepository = $NotificationRepository;
    }
    public function indexS()
    {


        $user = Auth::user()->id;
        $apoderados = [];

        $apoderadosF = Apoderado::where('id_u', $user)->get();
        if ($apoderadosF !== null) {

            foreach ($apoderadosF as $apoderado) {
                $celulares = Celular::where('id_u', $apoderado->id)->get();
                $celularesF = [];

                foreach ($celulares as $celular) {
                    $celularesF[] = [
                        'id' => $celular->id,
                        'celular' => $celular->celular,
                    ];
                }

                $apoderados[] = [
                    'id' => $user,
                    'nombre' => $apoderado->nombre,
                    'parentezco' => $apoderado->parentezco,
                    'apepat' => $apoderado->apepat,
                    'apemat' => $apoderado->apemat,
                    'fechanac' => $apoderado->fechanac,
                    'ci' => $apoderado->ci,
                    'nit' => $apoderado->nit,
                    'celular' => $celularesF,
                    'email' => $apoderado->email,
                ];
            }

        } else {
            $apoderados[] = null;
        }


        return view('apoderados.index', compact('apoderados'));
    }
    public function verE()
    {


        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Apoderados', 'url' => route('users.index')],
        ];
        $user = Auth::user()->id;
        $apoderados = [];
        $apoderadosF = Apoderado::where('id_u', $user)->get();
        if ($apoderadosF !== null) {

            foreach ($apoderadosF as $apoderado) {
                $celulares = Celular::where('id_u', $apoderado->id)->get();
                $celularesF = [];

                foreach ($celulares as $celular) {
                    $celularesF[] = [
                        'id' => $celular->id,
                        'celular' => $celular->celular,
                    ];
                }

                $apoderados[] = [
                    'id' => $user,
                    'nombre' => $apoderado->nombre,
                    'parentezco' => $apoderado->parentezco,
                    'apepat' => $apoderado->apepat,
                    'apemat' => $apoderado->apemat,
                    'fechanac' => $apoderado->fechanac,
                    'ci' => $apoderado->ci,
                    'nit' => $apoderado->nit,
                    'celular' => $celularesF,
                    'email' => $apoderado->email,
                ];
            }

        } else {
            $apoderados[] = null;
        }


        return view('apoderados.index', compact('apoderados', 'breadcrumb'));
    }

    public function create()
    {
        return view('apoderados.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'nombre' => 'required|string|max:255',
            'parentezco' => 'required|string|max:100',
            'apepat' => 'required|string|max:100',
            'apemat' => 'required|string|max:100',
            'fechanac' => 'required|date|before:today',
            'ci' => 'required|string|max:20|unique:apoderados,ci',
            'nit' => 'nullable|string|max:20|unique:apoderados,nit',
            'email' => 'required|email|max:255|unique:apoderados,email',
        ]);

        if (Auth::user()->hasRole('estudiante')) {

            $apoderado = Apoderado::create([
                'id_u' => Auth::user()->id,
                'nombre' => $request->nombre,
                'parentezco' => $request->parentezco,
                'apepat' => $request->apepat,
                'apemat' => $request->apemat,
                'fechanac' => $request->fechanac,
                'ci' => $request->ci,
                'nit' => $request->nit,
                'email' => $request->email,
            ]);
            foreach ($request->celulares as $celular) {
                Celular::create([

                    'id_u' => $apoderado->id,
                    'celular' => $celular,

                ]);
            }

            return redirect()->back()->with('status', 'Apoderado creado exitosamente');
        } else {
            return redirect()->back()->with('error', 'El registro es unicamente para los usuarios con el rol de Estudiante');

        }
    }

    public function edit(Apoderado $apoderado)
    {
        return view('apoderados.edit', compact('apoderado'));
    }

    public function update(Request $request, Apoderado $apoderado)
    {
        $apoderado->update($request->all());
        return redirect()->route('apoderados.index');
    }

    public function destroy(Apoderado $apoderado)
    {
        $apoderado->delete();
        return redirect()->route('apoderados.index');
    }
}
