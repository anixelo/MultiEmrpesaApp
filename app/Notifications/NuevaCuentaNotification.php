<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NuevaCuentaNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Company $company) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo'  => 'Nueva cuenta registrada',
            'mensaje' => "Se ha registrado una nueva cuenta: {$this->company->name}",
            'url'     => route('superadmin.companies.edit', $this->company->id),
        ];
    }
}
