<?php
namespace App\Services;

/**
 * Clase WhatsAppSender
 * 
 * Permite enviar mensajes de plantilla de WhatsApp usando la API Graph de Meta (Cloud API).
 */
class WhatsAppSender
{
    // ID del número de teléfono de WhatsApp (Phone Number ID proporcionado por Meta)
    private string $IDNUMBER;

    // Token de acceso de la API (access token de la app de Meta)
    private string $token;

    /**
     * Constructor
     * 
     * @param string $IDNUMBER Phone Number ID
     * @param string $token Access Token de la API de WhatsApp
     */
    public function __construct(string $IDNUMBER, string $token)
    {
        $this->IDNUMBER = $IDNUMBER;
        $this->token = $token;
    }

    /**
     * Envía un mensaje de plantilla a un número específico de WhatsApp.
     * 
     * @param string $phone Número destino en formato internacional (sin +)
     * @param string $template Nombre de la plantilla (ej. hello_world)
     * @param string $lang Código de idioma de la plantilla (ej. en_US)
     * @return array Resultado del envío (teléfono, código HTTP y respuesta)
     */
    private function EnviarMensaje(
        string $phone,
        string $template = 'hello_world',
        string $lang = 'en_US',
        array $parametersBody = [],
        ?string $headerImageLink = null,
        array $buttons = []
    ): array {
        $url = 'https://graph.facebook.com/v22.0/' . $this->IDNUMBER . '/messages';

        $components = [];

        // Header con imagen
        if ($headerImageLink) {
            $components[] = [
                "type" => "header",
                "parameters" => [
                    [
                        "type" => "image",
                        "image" => [
                            "link" => $headerImageLink
                        ]
                    ]
                ]
            ];
        }

        // Body con textos
        if (!empty($parametersBody)) {
            $components[] = [
                "type" => "body",
                "parameters" => $parametersBody
            ];
        }

        // Botones (pueden ser de tipo "quick_reply" o "call_to_action" según la plantilla)
        if (!empty($buttons)) {
            $components[] = [
                "type" => "button",
                "sub_type" => "quick_reply", // o "url" / "phone_number" según tu plantilla
                "index" => 0, // índice del botón en la plantilla (0, 1, 2)
                "parameters" => $buttons
            ];
        }

        $data = [
            "messaging_product" => "whatsapp",
            "to" => $phone,
            "type" => "template",
            "template" => [
                "name" => $template,
                "language" => ["code" => $lang],
                "components" => $components
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'phone' => $phone,
            'status' => $httpcode,
            'response' => $response
        ];
    }

    /**
     * Envía un mensaje de plantilla a múltiples números de WhatsApp.
     * 
     * @param array $phones Arreglo de números (en formato internacional sin +)
     * @param string $template Nombre de la plantilla a utilizar
     * @param string $lang Código de idioma (ej. en_US)
     */
    public function sendToMultiple(
        array $phones,
        string $template = 'hello_world',
        string $lang = 'en_US',
        array $parametersBody = [],
        ?string $headerImageLink = null,
        array $buttons = []
    ): array {
        $results = [];

        foreach ($phones as $phone) {
            $result = $this->EnviarMensaje(
                $phone,
                $template,
                $lang,
                $parametersBody,
                $headerImageLink,
                $buttons
            );
            $results[] = $result;
        }

        return $results;
    }
}
