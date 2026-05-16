<?php

namespace App\Notifications\Chat;

use App\Models\LiveSessionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LiveSessionConfirmTimeoutInApp extends Notification
{
    use Queueable;

    public function __construct(
        public LiveSessionRequest $liveSessionRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'live_session_confirm_timeout',
            'title' => 'Live session slot expired',
            'message' => 'You did not confirm in time and were removed from the queue.',
            'request_type' => 'live_session_queue',
            'request_id' => $this->liveSessionRequest->id,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'live_session_confirm_timeout',
            'title' => 'Live session slot expired',
            'message' => 'You did not confirm in time and were removed from the queue.',
            'request_type' => 'live_session_queue',
            'request_id' => $this->liveSessionRequest->id,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}
