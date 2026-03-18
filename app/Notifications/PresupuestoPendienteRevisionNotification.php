<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use MultiempresaApp\Presupuestos\Models\Presupuesto;

class PresupuestoPendienteRevisionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Presupuesto $presupuesto,
        public readonly User $worker
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo'  => 'Presupuesto pendiente de revisión',
            'mensaje' => "El trabajador {$this->worker->name} ha solicitado revisión del presupuesto {$this->presupuesto->numero}.",
            'url'     => route('admin.presupuestos.show', $this->presupuesto->id),
        ];
    }
}
