<?php

namespace App\Notifications\Chat;

use App\Models\ChatRoom;
use App\Models\PrayerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PrayerRepliedToSeekerInApp extends Notification
{
    use Queueable;

    public function __construct(
        public PrayerRequest $prayerRequest,
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
            'type' => 'prayer_replied',
            'title' => 'Prayer Replied',
            'message' => 'Clergy replied to your prayer request.',
            'chat_room_id' => $this->chatRoom->id,
            'request_type' => 'prayer',
            'request_id' => $this->prayerRequest->id,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'prayer_replied',
            'title' => 'Prayer Replied',
            'message' => 'Clergy replied to your prayer request.',
            'chat_room_id' => $this->chatRoom->id,
            'request_type' => 'prayer',
            'request_id' => $this->prayerRequest->id,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}

