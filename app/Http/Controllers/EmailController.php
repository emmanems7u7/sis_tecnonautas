<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Webklex\IMAP\Facades\Client;
class EmailController extends Controller
{
    public function fetchUnreadEmailsFromBCP()
    {
        // Conectar al servidor IMAP utilizando la cuenta configurada en el archivo de configuración
        $oClient = Client::account('gmail');
        $oClient->connect();


        $folder = $oClient->getFolder('INBOX');

        $message = $folder->query()->limit(1)->get()->first();





        dd($message);


    }
}
