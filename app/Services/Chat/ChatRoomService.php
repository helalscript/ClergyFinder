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
        $room = ChatRoom::firstOrCreate(
            [
                'type' => 'prayer',
                'prayer_request_id' => $prayerRequest->id,
            ],
            [
                'seeker_id' => $prayerRequest->seeker_id,
                'clergy_id' => $prayerRequest->clergy_id,
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

            ChatMessage::create([
                'chat_room_id' => $room->id,
                'sender_type' => 'clergy',
                'sender_id' => $prayerRequest->clergy_id,
                'body' => $prayerRequest->clergy_reply,
                'metadata' => null,
            ]);
        }

        return $room;
    }

    public function createLiveSessionChatRoom(LiveSessionRequest $liveSessionRequest): ChatRoom
    {
        $room = ChatRoom::firstOrCreate(
            [
                'type' => 'live_session',
                'live_session_request_id' => $liveSessionRequest->id,
            ],
            [
                'seeker_id' => $liveSessionRequest->seeker_id,
                'clergy_id' => $liveSessionRequest->clergy_id,
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

