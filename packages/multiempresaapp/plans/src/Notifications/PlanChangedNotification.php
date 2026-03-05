<?php

namespace MultiempresaApp\Plans\Notifications;

use MultiempresaApp\Plans\Models\Plan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlanChangedNotification extends Notification
{
    use Queueable;

    public function __construct(public Plan $plan) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Cambio de plan de suscripción')
            ->line('Tu plan ha sido cambiado a: ' . $this->plan->name)
            ->action('Ver Suscripción', url('/admin/subscription'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'plan_changed',
            'plan_id' => $this->plan->id,
            'message' => 'Plan cambiado a: ' . $this->plan->name,
            'url'     => '/admin/subscription',
        ];
    }
}
