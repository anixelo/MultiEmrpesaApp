<?php

namespace MultiempresaApp\Incidents\Notifications;

use MultiempresaApp\Incidents\Models\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewIncidentNotification extends Notification
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
            ->subject('Nueva Incidencia: ' . $this->incident->title)
            ->line('Se ha creado una nueva incidencia: ' . $this->incident->title)
            ->line('Prioridad: ' . $this->incident->priority_label)
            ->action('Ver Incidencia', url('/incidents/' . $this->incident->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'new_incident',
            'incident_id' => $this->incident->id,
            'title'       => $this->incident->title,
            'message'     => 'Nueva incidencia: ' . $this->incident->title,
            'url'         => '/incidents/' . $this->incident->id,
        ];
    }
}
