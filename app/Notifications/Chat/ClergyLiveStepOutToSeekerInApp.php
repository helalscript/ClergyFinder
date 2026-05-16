<?php

namespace App\Notifications\Chat;

use App\Models\Clergy;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ClergyLiveStepOutToSeekerInApp extends Notification
{
    use Queueable;

    public function __construct(
        public Clergy $clergy,
        public ?string $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'clergy_live_step_out',
            'title' => 'Clergy temporarily unavailable',
            'message' => $this->message ?: 'The clergy member stepped away. You may stay in queue or leave.',
            'request_type' => 'live_session_queue',
            'clergy_id' => $this->clergy->id,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'clergy_live_step_out',
            'title' => 'Clergy temporarily unavailable',
            'message' => $this->message ?: 'The clergy member stepped away. You may stay in queue or leave.',
            'request_type' => 'live_session_queue',
            'clergy_id' => $this->clergy->id,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}
