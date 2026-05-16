<?php

namespace App\Notifications\Chat;

use App\Models\LiveSessionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LiveSessionQueueAbandonedInApp extends Notification
{
    use Queueable;

    public function __construct(
        public LiveSessionRequest $liveSessionRequest,
        public string $reason
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'live_session_queue_abandoned',
            'title' => 'Removed from live session queue',
            'message' => $this->reason,
            'request_type' => 'live_session_queue',
            'request_id' => $this->liveSessionRequest->id,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'live_session_queue_abandoned',
            'title' => 'Removed from live session queue',
            'message' => $this->reason,
            'request_type' => 'live_session_queue',
            'request_id' => $this->liveSessionRequest->id,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}
