<?php

namespace App\Notifications\Chat;

use App\Models\LiveSessionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LiveSessionSeekerConfirmedReadyInApp extends Notification
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
            'type' => 'live_session_seeker_confirmed_ready',
            'title' => 'Seeker is ready',
            'message' => 'The seeker confirmed they are ready. You can start the live video call.',
            'request_type' => 'live_session_queue',
            'request_id' => $this->liveSessionRequest->id,
            'agora_channel' => $this->liveSessionRequest->agora_channel,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'live_session_seeker_confirmed_ready',
            'title' => 'Seeker is ready',
            'message' => 'The seeker confirmed they are ready. You can start the live video call.',
            'request_type' => 'live_session_queue',
            'request_id' => $this->liveSessionRequest->id,
            'agora_channel' => $this->liveSessionRequest->agora_channel,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}
