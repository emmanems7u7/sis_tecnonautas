<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ModuloNoexiste extends Notification
{
    use Queueable;
    protected $user, $materia, $modulosig, $nombreM;
    /**
     * Create a new notification instance.
     */
    public function __construct($user, $materia, $moduloSiguiente, $modulo)
    {
        $this->user = $user;
        $this->materia = $materia;
        $this->modulosig = $moduloSiguiente;
        $this->nombreM = $modulo;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return $notifiable->preferredNotificationChannels();
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }
    public function toDatabase($notifiable)
    {

        return [
            'message' => 'El usuario ' . $this->user->usuario_nombres . '' . $this->user->usuario_app . ' ' .
                $this->user->usuario_apm . ', intentó asignar automaticamente a sus estudiantes de la materia ' . $this->materia->nombre . ', ' . $this->nombreM . ', el cual la materia no cuenta con un ' . $this->modulosig . 'cree el modulo para que el usuario pueda continuar con su asignacion automatica',
            'action_url' => route('modulos.materia.show', [
                'id_a' => $this->materia->id,

            ]),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
