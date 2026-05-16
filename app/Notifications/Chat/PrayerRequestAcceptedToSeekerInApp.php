<?php

namespace App\Notifications\Chat;

use App\Models\ChatRoom;
use App\Models\PrayerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PrayerRequestAcceptedToSeekerInApp extends Notification
{
    use Queueable;

    public function __construct(
        public PrayerRequest $prayerRequest,
        public ChatRoom $chatRoom
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'prayer_request_accepted',
            'title' => 'Prayer request accepted',
            'message' => 'Your prayer request was accepted. You can chat now.',
            'request_type' => 'prayer',
            'request_id' => $this->prayerRequest->id,
            'chat_room_id' => $this->chatRoom->id,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'prayer_request_accepted',
            'title' => 'Prayer request accepted',
            'message' => 'Your prayer request was accepted. You can chat now.',
            'request_type' => 'prayer',
            'request_id' => $this->prayerRequest->id,
            'chat_room_id' => $this->chatRoom->id,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}
