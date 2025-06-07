<?php

namespace App\Http\Controllers;

use App\Models\Correo;
use App\Models\PlantillaCorreo;
use App\Models\VariablesPlantillas;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Interfaces\CorreoInterface;
use App\Mail\MiMailable;
use Illuminate\Support\Facades\Mail;
use App\Models\Pago;
use Carbon\Carbon;

class CorreoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $correoRepository;
    public function __construct(CorreoInterface $CorreoInterface)
    {

        $this->correoRepository = $CorreoInterface;
    }
    public function index()
    {
        $emails = PlantillaCorreo::all();
        $variablesPlantilla = VariablesPlantillas::all();
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Correos', 'url' => route('correos.index')],
        ];

        return view('emails.index', compact('emails', 'breadcrumb', 'variablesPlantilla'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function GetPlantilla($id)
    {
        try {
            $email = PlantillaCorreo::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'email' => $email
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'mensaje' => 'Plantilla no encontrada.'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Correo $correo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Correo $correo)
    {
        //
    }
    public function update_plantilla(Request $request, $id)
    {

        try {
            $email = PlantillaCorreo::findOrFail($id);

            $request->validate([
                'nombre_plantilla' => 'required|string|max:255',
                'asunto_plantilla' => 'required|string|max:255',
                'contenido' => 'required|string',
            ]);

            $this->correoRepository->EditarPlantillaCorreo($request, $email);

            return redirect()->route('correos.index');

        } catch (ModelNotFoundException $e) {

            return redirect()->route('correos.index')
                ->with('error', 'La plantilla de correo no fue encontrada.');
        }


    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Correo $correo)
    {
        //
    }



    public function enviarCorreo()
    {
        $detalle = [
            'titulo' => 'Título del correo',
            'cuerpo' => 'Este es el cuerpo del correo.'
        ];


        Mail::to('Emmanuelz7u7@gmail.com')->send(new MiMailable($detalle));

        return 'Correo enviado';
    }

    public function getMails(Request $request)
    {
        // Validar las fechas
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'email' => 'nullable|email',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $emailFilter = $request->input('email');


        $messages = $this->CorreoRepository->getMails($emailFilter, $startDate, $endDate);

        // Crear una estructura para almacenar los correos
        $emails = [];

        foreach ($messages as $message) {
            $date = $message->getDate()->first();
            $formattedDate = $date instanceof \Carbon\Carbon ? $date->format('d M Y H:i:s') : 'Fecha no disponible';
            $subject = $message->getSubject()->first();
            $body = $message->getHTMLBody();

            $emails[] = [
                'subject' => $subject,
                'date' => $formattedDate,
                'from' => $message->getFrom()[0]->mail,
                'body' => $body,
            ];
        }

        // Devolver los resultados como JSON
        return response()->json($emails);
    }

    public function showFilterForm()
    {
        // Obtener la fecha actual
        // $currentDate = now()->format('Y-m-d');
        $currentDate = '2024-01-03';
        // Mostrar la vista con correos de la fecha actual
        return view('mail.correos', ['start_date' => $currentDate, 'end_date' => $currentDate]);
    }


    // Asegúrate de definir este método en el controlador
    private function cleanHTML($html)
    {
        // Aquí podrías usar una biblioteca o una función personalizada para limpiar el HTML
        // Este ejemplo es básico y se puede mejorar según tus necesidades
        $doc = new \DOMDocument();
        @$doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Opcional: eliminar etiquetas específicas
        $tagsToRemove = ['style', 'script', 'iframe'];
        foreach ($tagsToRemove as $tag) {
            $nodes = $doc->getElementsByTagName($tag);
            while ($nodes->length > 0) {
                $node = $nodes->item(0);
                $node->parentNode->removeChild($node);
            }
        }

        // Limpiar contenido
        $body = $doc->getElementsByTagName('body')->item(0);
        return $body ? $doc->saveHTML($body) : '';
    }



    // Filtrar correos por fechas
    public function filterMails(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'correos' => 'required|array',
            'correos.*' => 'exists:pagos,id', // Validar cada ID de correo
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $selectedEmailIds = $request->input('correos');

        // Obtener los correos seleccionados desde la base de datos
        $emails = Pago::whereIn('id', $selectedEmailIds)->pluck('email')->toArray();

        // Crear un cliente IMAP
        $client = Client::account('default');
        $client->connect();
        $folder = $client->getFolder('INBOX');

        // Construir la consulta
        $query = $folder->query()->since($startDate)->before($endDate);

        // Filtrar correos por la lista obtenida desde la base de datos
        foreach ($emails as $email) {
            $query->from($email);
        }

        // Obtener mensajes filtrados
        $messages = $query->get()->sortByDesc('date');
        $result = [];

        // Procesar los mensajes obtenidos
        foreach ($messages as $message) {
            $date = $message->getDate()->first();
            $formattedDate = $date instanceof Carbon ? $date->format('d M Y H:i:s') : 'Fecha no disponible';
            $subject = $message->getSubject()->first();
            $body = $message->getHTMLBody();

            $result[] = [
                'subject' => $subject,
                'date' => $formattedDate,
                'from' => $message->getFrom()[0]->mail,
                'body' => $body,
            ];
        }

        // Devolver los resultados como JSON
        return response()->json($result);
    }


    public function filtraIndex()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Correos', 'url' => route('emails.index')],
        ];
        $correos = Pago::all();
        return view('mail.obtenercorreos', compact('correos', 'breadcrumb'));
    }
}
