<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use MultiempresaApp\Presupuestos\Models\Presupuesto;

class PresupuestoEstadoNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Presupuesto $presupuesto) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $estado   = $this->presupuesto->estado;
        $etiqueta = $estado === 'aceptado' ? 'aceptado' : 'rechazado';

        return [
            'titulo'  => "Presupuesto {$etiqueta}",
            'mensaje' => "El presupuesto {$this->presupuesto->numero} ha sido {$etiqueta} por el cliente.",
            'url'     => route('admin.presupuestos.show', $this->presupuesto->id),
        ];
    }
}
