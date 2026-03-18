<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use MultiempresaApp\Presupuestos\Models\Presupuesto;

class PresupuestoRevisionResultadoNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Presupuesto $presupuesto,
        public readonly string $resultado,
        public readonly ?string $nota = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        if ($this->resultado === 'validado') {
            $titulo  = 'Presupuesto validado';
            $mensaje = "El presupuesto {$this->presupuesto->numero} ha sido validado por un administrador.";
        } elseif ($this->resultado === 'rechazado_revision') {
            $titulo  = 'Revisión de presupuesto rechazada';
            $mensaje = "La revisión del presupuesto {$this->presupuesto->numero} ha sido rechazada. El presupuesto ha vuelto a borrador.";
        } else {
            $titulo  = 'Presupuesto vuelto a borrador';
            $mensaje = "El presupuesto {$this->presupuesto->numero} ha sido devuelto a estado borrador.";
        }

        return [
            'titulo'  => $titulo,
            'mensaje' => $mensaje,
            'url'     => route('admin.presupuestos.show', $this->presupuesto->id),
        ];
    }
}
