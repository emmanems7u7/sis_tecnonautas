<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PagoNoAutomatizado extends Notification
{
    use Queueable;
    protected $pago_id, $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($pago_id, $user)
    {
        $this->pago_id = $pago_id;
        $this->user = $user;
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
            'message' => 'El estudiante ' . $this->user->usuario_nombres . ' ' . $this->user->usuario_app . ' ' . $this->user->usuario_apm .
                ' intentó validar automáticamente un pago asociado, pero no se logró completar el proceso correctamente. ' .
                'Se recomienda revisar manualmente el pago para asegurar la correcta validación.',

            'action_url' => route('auditoria.index', ['id' => $this->pago_id]),
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
