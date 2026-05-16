<?php

namespace App\Services\Chat;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\LiveSessionRequest;
use App\Models\PrayerRequest;

class ChatRoomService
{
    public function createPrayerChatRoom(PrayerRequest $prayerRequest): ChatRoom
    {
        $clergyUserId = $prayerRequest->clergy_user_id ?: $prayerRequest->clergyProfile?->user_id;
        if (! $clergyUserId) {
            throw new \InvalidArgumentException('Prayer request is missing clergy user.');
        }

        $room = ChatRoom::firstOrCreate(
            [
                'type' => 'prayer',
                'prayer_request_id' => $prayerRequest->id,
            ],
            [
                'seeker_id' => $prayerRequest->seeker_id,
                'clergy_id' => $clergyUserId,
                'status' => 'active',
                'video_status' => 'not_started',
            ]
        );

        if ($room->wasRecentlyCreated) {
            ChatMessage::create([
                'chat_room_id' => $room->id,
                'sender_type' => 'seeker',
                'sender_id' => $prayerRequest->seeker_id,
                'body' => $prayerRequest->message,
                'metadata' => null,
            ]);

            if (! empty($prayerRequest->clergy_reply)) {
                ChatMessage::create([
                    'chat_room_id' => $room->id,
                    'sender_type' => 'clergy',
                    'sender_id' => $clergyUserId,
                    'body' => $prayerRequest->clergy_reply,
                    'metadata' => null,
                ]);
            }
        }

        return $room;
    }

    public function createLiveSessionChatRoom(LiveSessionRequest $liveSessionRequest): ChatRoom
    {
        $clergyUserId = $liveSessionRequest->clergy_user_id ?: $liveSessionRequest->clergyProfile?->user_id;
        if (! $clergyUserId) {
            throw new \InvalidArgumentException('Live session request is missing clergy user.');
        }

        $room = ChatRoom::firstOrCreate(
            [
                'type' => 'live_session',
                'live_session_request_id' => $liveSessionRequest->id,
            ],
            [
                'seeker_id' => $liveSessionRequest->seeker_id,
                'clergy_id' => $clergyUserId,
                'status' => 'active',
                'video_status' => 'not_started',
            ]
        );

        if ($room->wasRecentlyCreated) {
            ChatMessage::create([
                'chat_room_id' => $room->id,
                'sender_type' => 'seeker',
                'sender_id' => $liveSessionRequest->seeker_id,
                'body' => $liveSessionRequest->message,
                'metadata' => null,
            ]);

            ChatMessage::create([
                'chat_room_id' => $room->id,
                'sender_type' => 'system',
                'sender_id' => null,
                'body' => 'Live session scheduled.',
                'metadata' => [
                    'meeting_link' => $liveSessionRequest->meeting_link,
                    'scheduled_time' => optional($liveSessionRequest->scheduled_time)->toIso8601String(),
                ],
            ]);
        }

        return $room;
    }

    public function addSystemMessage(ChatRoom $room, string $body, ?array $metadata = null): ChatMessage
    {
        return ChatMessage::create([
            'chat_room_id' => $room->id,
            'sender_type' => 'system',
            'sender_id' => null,
            'body' => $body,
            'metadata' => $metadata,
        ]);
    }
}
