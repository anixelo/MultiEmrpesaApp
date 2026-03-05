<?php
namespace App\Notifications;

use App\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IncidentResolvedNotification extends Notification
{
    use Queueable;

    public function __construct(public Incident $incident) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Incidencia Resuelta: ' . $this->incident->title)
            ->line('Tu incidencia ha sido resuelta: ' . $this->incident->title)
            ->action('Ver Incidencia', url('/incidents/' . $this->incident->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'incident_resolved',
            'incident_id' => $this->incident->id,
            'message' => 'Incidencia resuelta: ' . $this->incident->title,
            'url' => '/incidents/' . $this->incident->id,
        ];
    }
}
