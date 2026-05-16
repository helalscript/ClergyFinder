<?php

namespace App\Notifications\Chat;

use App\Models\LiveSessionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LiveSessionYourTurnInApp extends Notification
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
            'type' => 'live_session_your_turn',
            'title' => 'You are up for a live session',
            'message' => 'It is your turn. Tap "I confirm I am ready" to continue.',
            'request_type' => 'live_session_queue',
            'request_id' => $this->liveSessionRequest->id,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'live_session_your_turn',
            'title' => 'You are up for a live session',
            'message' => 'It is your turn. Tap "I confirm I am ready" to continue.',
            'request_type' => 'live_session_queue',
            'request_id' => $this->liveSessionRequest->id,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}
