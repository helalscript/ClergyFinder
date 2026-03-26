<?php

namespace App\Notifications\Chat;

use App\Models\LiveSessionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewLiveSessionRequestToClergyInApp extends Notification
{
    use Queueable;

    public function __construct(
        public LiveSessionRequest $liveSessionRequest
    ) {
    }

    public function via(object $notifiable): array
    {
        // Email আলাদা করে queue করা হচ্ছে; এখানে শুধুই in-app (database + broadcast)
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'new_live_session_request',
            'title' => 'New Live Session Request',
            'message' => 'A seeker requested a live session.',
            'request_type' => 'live_session',
            'request_id' => $this->liveSessionRequest->id,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'new_live_session_request',
            'title' => 'New Live Session Request',
            'message' => 'A seeker requested a live session.',
            'request_type' => 'live_session',
            'request_id' => $this->liveSessionRequest->id,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}

