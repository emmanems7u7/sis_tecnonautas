<?php
namespace App\Repositories;

use App\Interfaces\CorreoInterface;
use \App\Models\ConfCorreo;
use \App\Models\CuentaImap;

use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Config;
use Webklex\IMAP\Exceptions\ConnectionFailedException;
use Illuminate\Support\Facades\Log;
use Webklex\IMAP\ClientManager;
class CorreoRepository extends BaseRepository implements CorreoInterface
{

    public function __construct()
    {
        parent::__construct();

    }
    public function EditarPlantillaCorreo($request, $email)
    {
        $email->nombre = $request->input('nombre_plantilla');
        $email->asunto = $request->input('asunto_plantilla');
        $email->contenido = $request->input('contenido');
        $email->save();
    }
    public function EditarConfCorreo($correoId, $request)
    {
        $confCorreo = ConfCorreo::updateOrCreate(

            ['id' => $correoId],
            [
                'conf_protocol' => $this->cleanHtml($request['conf_correo_protocol']),
                'conf_smtp_host' => $this->cleanHtml($request['conf_smtp_host']),
                'conf_smtp_port' => $this->cleanHtml($request['conf_smtp_port']),
                'conf_smtp_user' => $this->cleanHtml($request['conf_smtp_user']),
                'conf_smtp_pass' => $this->cleanHtml($request['conf_smtp_pass']),
                'conf_mailtype' => $this->cleanHtml($request['conf_mailtype']),
                'conf_charset' => $this->cleanHtml($request['conf_charset']),
                'conf_in_background' => $request['conf_in_background'],
                'accion_usuario' => Auth::user()->name,
            ]
        );

        return $confCorreo;
    }

    function getMails($email, $fecha_inicio, $fecha_fin)
    {
        $cuenta = CuentaImap::first();

        if (!$cuenta) {
            throw new \Exception("No hay configuración IMAP guardada");
        }

        // Crear un cliente IMAP con configuración dinámica
        Config::set('imap.accounts.default', [
            'host' => $cuenta->host,
            'port' => $cuenta->port,
            'encryption' => $cuenta->encryption ?: null,
            'validate_cert' => $cuenta->validate_cert,
            'username' => $cuenta->username,
            'password' => $cuenta->password,
            'protocol' => 'imap',
        ]);
        $client = Client::account('default');
        $client->connect();

        // Obtener la bandeja de entrada
        $folder = $client->getFolder('INBOX');

        // Construir la consulta
        $query = $folder->query()->since($fecha_inicio)->before($fecha_fin);

        if ($email) {
            $query->from($email);
        }

        $messages = $query->get()->sortByDesc('date');

        return $messages;
    }

    function getMailsByDate($fecha_inicio, $fecha_fin)
    {
        try {

            $cuenta = CuentaImap::first();

            if (!$cuenta) {
                throw new \Exception("No hay configuración IMAP guardada");
            }

            // Crear un cliente IMAP
            $client = Client::account('default');
            $client->connect();

            // Obtener la bandeja de entrada
            $folder = $client->getFolder('INBOX');

            // Construir la consulta solo por fechas
            $query = $folder->query()->since($fecha_inicio)->before($fecha_fin);

            // Obtener y ordenar los mensajes
            $messages = $query->get()->sortByDesc('date');

            return $messages;

        } catch (ConnectionFailedException $e) {
            Log::error("Fallo la conexión IMAP: " . $e->getMessage());
            return collect(); // Retorna colección vacía o null si preferís
        } catch (\Exception $e) {
            Log::error("Error al obtener correos: " . $e->getMessage());
            return collect();
        }
    }



    function CorreoPagos($email_apoderado, $fecha_pago, $fecha_fin)
    {


        $messages = $this->getMails($email_apoderado, $fecha_pago, $fecha_fin);

        $listaCorreos = [];
        foreach ($messages as $message) {
            $date = $message->getDate()->first();
            $subject = $message->getSubject()->first();
            $text = $message->getBodies();
            $body = $message->getHTMLBody();

            $remitente = $this->extraerDato($body, '/<a\s+href=["\']mailto:([^"\']+)["\']/');
            $fecha = $this->extraerDato($body, '/<td\s+align="right">\s*<font[^>]*>\s*([\d\/]+\s+\d{2}:\d{2})\s*<\/font>/');

            $asunto = $this->extraerDato($body, '/Subject:\s*([^<]+)/');
            $numTransaccion = $this->extraerDato($body, '/N° Transacción:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>(\d+)/');
            $numComprobante = $this->extraerDato($body, '/N° Comprobante:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>(\d+)/');
            $origenTitular = $this->extraerDato($body, '/Titular:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>([^<]+)/');
            $origenCuenta = $this->extraerDato($body, '/Cuenta:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>(\d+)/');
            $destinoBeneficiario = $this->extraerDato($body, '/Beneficiario:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>([^<]+)/');
            $destinoCuenta = $this->extraerDato($body, '/Cuenta:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>BIL<\/font><br>\s*<font[^>]*>(\d+)/');
            $montoTransferido = $this->extraerDato($body, '/Monto transferido:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>\s*(?:BS|BOL)?\s*([\d,.]+)/i');
            $glosa = $this->extraerDato($body, '/Glosa:\s*<\/font>\s*<\/td>\s*<td[^>]*>\s*<font[^>]*>([^<]+)/');

            $datosCorreo = [
                'remitente' => $remitente,
                'fecha' => $fecha,
                'asunto' => $asunto,
                'numTransaccion' => $numTransaccion,
                'numComprobante' => $numComprobante,
                'origenTitular' => $origenTitular,
                'origenCuenta' => $origenCuenta,
                'destinoBeneficiario' => $destinoBeneficiario,
                'destinoCuenta' => $destinoCuenta,
                'montoTransferido' => $montoTransferido,
                'glosa' => $glosa
            ];

            // Validar si algún campo es null
            if (!in_array(null, $datosCorreo, true)) {
                $listaCorreos[] = $datosCorreo;
            }
        }
        return $listaCorreos;
    }
    function extraerDato($body, $pattern)
    {
        if (preg_match($pattern, $body, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }
}
