<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HabilitarEstudiante extends Notification
{
    use Queueable;
    protected $useriD;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->useriD = $userId;
    }
    public function via($notifiable)
    {
        return $notifiable->preferredNotificationChannels();
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Habilitar Estudiante')
            ->line('Un estudiante se ha registrado en una materia y necesita ser habilitado.')
            ->action('Habilitar Estudiante', url('/ruta/para/habilitar/estudiante'))
            ->line('Gracias por usar nuestra aplicación!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Un estudiante se ha registrado en una materia y necesita ser habilitado.',
            'action_url' => route('estudiantesinactivos.show', [
                'UserId' => $this->useriD,

            ]),
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
