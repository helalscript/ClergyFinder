<?php

namespace App\Notifications\Chat;

use App\Models\PrayerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewPrayerRequestToClergyInApp extends Notification
{
    use Queueable;

    public function __construct(
        public PrayerRequest $prayerRequest
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
            'type' => 'new_prayer_request',
            'title' => 'New Prayer Request',
            'message' => 'A seeker submitted a prayer request.',
            'request_type' => 'prayer',
            'request_id' => $this->prayerRequest->id,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'new_prayer_request',
            'title' => 'New Prayer Request',
            'message' => 'A seeker submitted a prayer request.',
            'request_type' => 'prayer',
            'request_id' => $this->prayerRequest->id,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}

