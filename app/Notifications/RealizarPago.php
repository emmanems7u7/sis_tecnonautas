<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RealizarPago extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable->preferredNotificationChannels();
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Realizar Pago')
            ->line('Tu inscripción fue exitosa debes realizar el Pago de la materia!')
            ->action('Registrar Apoderados', url('/ruta/para/registrar/apoderados'))
            ->line('Gracias por usar nuestra aplicación!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Tu inscripcion fue exitosa, ahora debes realizar el Pago de la materia!',

            'action_url' => route('Pago.pendiente.index'),
            'btn-action' => 'btn btn-warning',
        ];
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
