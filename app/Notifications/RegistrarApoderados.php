<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrarApoderados extends Notification
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
    public function via($notifiable)
    {
        return $notifiable->preferredNotificationChannels();
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Registrar Apoderados')
            ->line('Por favor, registra a tus apoderados (mamá o papá) en la plataforma.')
            ->action('Registrar Apoderados', url('/ruta/para/registrar/apoderados'))
            ->line('Gracias por usar nuestra aplicación!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Por favor, registra a tus apoderados (mamá o papá) en la plataforma.',
            'action_url' => route('apoderados.index'),
            'btn-action' => 'btn btn-info',
        ];
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
