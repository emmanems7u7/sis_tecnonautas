<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppSender;

class PruebaWhatsapp extends Controller
{


    function prueba()
    {

        $token = 'EAAeypxrgMSkBO2dDu2n02ZClTxyNNiPEkXkrbqIAQ73P52iG0FjqKdhjZAj6gO5Rr1mCxu4x8YMPjynHQ3Kf2YXxQ8FZBHZB3aZAw4y6lZChLCZBwHheVzUGnHtCEVO67jSN32KBwZAQ3MXwTSbfIjrbGiJcxHsdx6tmNQdO1jiOXQvRkeYJCoYU88bS5WCh7dMEhx23JHXbHjw8sPCcPPlbb14Rv2mlk6AsYsn4';
        $IDNUMBER = '673035315893435';

        $sender = new WhatsAppSender($IDNUMBER, $token);


        $numeros = [
            '59178777346',
            '59170190260',
            '59172018907'
        ];

        $parameters = [
            ["type" => "text", "text" => "123"],
            ["type" => "text", "text" => "321"],

        ];
        $template = 'registro';
        $idioma = 'en_US';


        $r = $sender->sendToMultiple($numeros, $template, $idioma, $parameters);

        return response()->json(['message' => $r]);
    }
}
