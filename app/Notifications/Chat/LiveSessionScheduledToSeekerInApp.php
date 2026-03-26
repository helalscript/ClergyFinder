<?php

namespace App\Notifications\Chat;

use App\Models\ChatRoom;
use App\Models\LiveSessionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class LiveSessionScheduledToSeekerInApp extends Notification
{
    use Queueable;

    public function __construct(
        public LiveSessionRequest $liveSessionRequest,
        public ChatRoom $chatRoom
    ) {
    }

    public function via(object $notifiable): array
    {
        // Email আলাদা করে পাঠানো হচ্ছে; এখানে শুধুই in-app (database + broadcast)
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'live_session_scheduled',
            'title' => 'Live Session Scheduled',
            'message' => 'Your live session has been scheduled.',
            'chat_room_id' => $this->chatRoom->id,
            'request_type' => 'live_session',
            'request_id' => $this->liveSessionRequest->id,
            'meeting_link' => $this->liveSessionRequest->meeting_link,
            'scheduled_time' => optional($this->liveSessionRequest->scheduled_time)->toIso8601String(),
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'live_session_scheduled',
            'title' => 'Live Session Scheduled',
            'message' => 'Your live session has been scheduled.',
            'chat_room_id' => $this->chatRoom->id,
            'request_type' => 'live_session',
            'request_id' => $this->liveSessionRequest->id,
            'meeting_link' => $this->liveSessionRequest->meeting_link,
            'scheduled_time' => optional($this->liveSessionRequest->scheduled_time)->toIso8601String(),
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}

