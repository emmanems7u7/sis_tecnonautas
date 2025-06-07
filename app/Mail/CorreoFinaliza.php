<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CorreoFinaliza extends Mailable
{
    use Queueable, SerializesModels;

    public $detalle;

    /**
     * Crear una nueva instancia del mensaje.
     *
     * @param array $detalle
     * @return void
     */
    public function __construct($detalle)
    {
        $this->detalle = $detalle;
    }

    /**
     * Construir el mensaje.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.finaliza')
            ->subject('Finalización de curso')
            ->with('detalle', $this->detalle);
    }
}
