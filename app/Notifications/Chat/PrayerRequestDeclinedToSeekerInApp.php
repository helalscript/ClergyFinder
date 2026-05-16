<?php

namespace App\Notifications\Chat;

use App\Models\PrayerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PrayerRequestDeclinedToSeekerInApp extends Notification
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
            'type' => 'prayer_request_declined',
            'title' => 'Prayer request declined',
            'message' => $this->prayerRequest->declined_reason
                ? 'Your request was declined: '.$this->prayerRequest->declined_reason
                : 'Your prayer request was declined.',
            'request_type' => 'prayer',
            'request_id' => $this->prayerRequest->id,
            'url' => '',
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'prayer_request_declined',
            'title' => 'Prayer request declined',
            'message' => $this->prayerRequest->declined_reason
                ? 'Your request was declined: '.$this->prayerRequest->declined_reason
                : 'Your prayer request was declined.',
            'request_type' => 'prayer',
            'request_id' => $this->prayerRequest->id,
            'url' => '',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]);
    }
}
