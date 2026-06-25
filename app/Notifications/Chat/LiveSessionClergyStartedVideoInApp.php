<?php

namespace App\Notifications\Chat;

use App\Models\LiveSessionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LiveSessionClergyStartedVideoInApp extends Notification
{
    use Queueable;

    /**
     * @param  array{channel: string, uid: int|string, token: string, expires_at: string, live_session_request_id: int, join_url: string}|null  $agoraJoin
     */
    public function __construct(
        public LiveSessionRequest $liveSessionRequest,
        public ?array $agoraJoin = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return $this->payload();
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage(array_merge($this->payload(), [
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png'),
        ]));
    }

    /** @return array<string, mixed> */
    protected function payload(): array
    {
        $data = [
            'type' => 'live_session_clergy_started_video',
            'title' => 'Live session started',
            'message' => 'The clergy started the video call. Join now.',
            'request_type' => 'live_session_queue',
            'request_id' => $this->liveSessionRequest->id,
            'agora_channel' => $this->liveSessionRequest->agora_channel,
            'url' => $this->agoraJoin['join_url'] ?? '',
        ];

        if ($this->agoraJoin) {
            $data['agora_join'] = $this->agoraJoin;
        }

        return $data;
    }
}
