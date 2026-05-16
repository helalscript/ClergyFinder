<?php

namespace App\Notifications\Chat;

use App\Models\PrayerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PrayerRequestClosedToSeekerInApp extends Notification
{
    use Queueable;

    public function __construct(
        public PrayerRequest $prayerRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'prayer_request_closed',
            'title' => 'Prayer conversation closed',
            'message' => 'The clergy member closed this prayer thread.',
            'request_type' => 'prayer',
            'request_id' => $this->prayerRequest->id,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'prayer_request_closed',
            'title' => 'Prayer conversation closed',
            'message' => 'The clergy member closed this prayer thread.',
            'request_type' => 'prayer',
            'request_id' => $this->prayerRequest->id,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}
