<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PresupuestoTrabajadorNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $numero,
        public readonly ?int $presupuestoId,
        public readonly User $worker,
        public readonly string $accion
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $accionLabel = $this->accion === 'creado' ? 'creado' : 'eliminado';

        return [
            'titulo'  => "Presupuesto {$accionLabel} por trabajador",
            'mensaje' => "El trabajador {$this->worker->name} ha {$accionLabel} el presupuesto {$this->numero}.",
            'url'     => ($this->accion === 'creado' && $this->presupuestoId)
                ? route('admin.presupuestos.show', $this->presupuestoId)
                : route('admin.presupuestos.index'),
        ];
    }
}
