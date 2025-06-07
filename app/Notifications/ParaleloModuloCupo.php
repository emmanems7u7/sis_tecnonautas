<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParaleloModuloCupo extends Notification
{
    use Queueable;

    protected $asignacion;
    protected $modulo;
    protected $paralelo;

    /**
     * Create a new notification instance.
     */
    public function __construct($asignacion, $modulo, $paralelo)
    {

        $this->asignacion = $asignacion;
        $this->modulo = $modulo;
        $this->paralelo = $paralelo;
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
    public function toDatabase()
    {

        return [
            'message' => 'La materia ' . $this->asignacion->nombre . ' ' . $this->modulo->nombreM . ' paralelo ' . '"' . $this->paralelo->nombre . '"' . ' alcanzó su cupo maximo de estudiantes, cree otro paralelo o gestione la capacidad de los cupos de los paralelos',
            'action_url' => route('Paralelos.modulos.show', [
                'id_a' => $this->asignacion->id,
                'id_m' => $this->modulo->id
            ]),
            'btn-action' => 'btn btn-warning',
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
