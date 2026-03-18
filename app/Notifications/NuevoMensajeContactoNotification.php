<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NuevoMensajeContactoNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly ContactMessage $mensaje) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo'  => 'Nuevo mensaje de contacto',
            'mensaje' => "Mensaje de {$this->mensaje->name}: {$this->mensaje->subject}",
            'url'     => route('superadmin.contact-messages.show', $this->mensaje->id),
        ];
    }
}
