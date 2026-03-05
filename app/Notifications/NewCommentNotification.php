<?php
namespace App\Notifications;

use App\Models\IncidentComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public function __construct(public IncidentComment $comment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_comment',
            'incident_id' => $this->comment->incident_id,
            'comment_id' => $this->comment->id,
            'message' => 'Nuevo comentario en: ' . $this->comment->incident->title,
            'url' => '/incidents/' . $this->comment->incident_id,
        ];
    }
}
