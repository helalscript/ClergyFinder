<?php

namespace App\Http\Controllers\API\V1\Chat;

use App\Events\MessageEvent;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chat\ChatBlockRequest;
use App\Http\Requests\Api\Chat\ChatPresenceRequest;
use App\Http\Requests\Api\Chat\SendChatMessageRequest;
use App\Models\ChatBlock;
use App\Models\ChatMessage;
use App\Models\ChatPresence;
use App\Models\ChatRoom;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    private function onlineThresholdSeconds(): int
    {
        // Presence update না করলে offline ধরবে।
        return 120;
    }

    private function getRoomOr404(int $roomId): ChatRoom
    {
        return ChatRoom::findOrFail($roomId);
    }

    private function authorizeParticipant(ChatRoom $room, $user): bool
    {
        return $user->role === 'admin'
            || (int) $user->id === (int) $room->seeker_id
            || (int) $user->id === (int) $room->clergy_id;
    }

    private function otherParticipantId(ChatRoom $room, $userId): ?int
    {
        if ((int) $userId === (int) $room->seeker_id) {
            return (int) $room->clergy_id;
        }
        if ((int) $userId === (int) $room->clergy_id) {
            return (int) $room->seeker_id;
        }

        return null;
    }

    private function isBlocked(ChatRoom $room, int $senderId, int $otherId): bool
    {
        return ChatBlock::where('chat_room_id', $room->id)
            ->where(function ($q) use ($senderId, $otherId) {
                $q->where(function ($q2) use ($senderId, $otherId) {
                    $q2->where('blocker_id', $senderId)->where('blocked_id', $otherId);
                })->orWhere(function ($q2) use ($senderId, $otherId) {
                    $q2->where('blocker_id', $otherId)->where('blocked_id', $senderId);
                });
            })
            ->exists();
    }

    public function rooms(): JsonResponse
    {
        $user = auth('api')->user();
        $per_page = request()->has('per_page') ? (int) request()->per_page : 15;

        $query = ChatRoom::with([
            'prayerRequest:id,message,clergy_reply,status',
            'liveSessionRequest:id,message,meeting_link,scheduled_time,status',
        ])->latest('id');

        if ($user->role !== 'admin') {
            if ($user->role === 'seeker') {
                $query->where('seeker_id', $user->id);
            } elseif ($user->role === 'clergy') {
                $query->where('clergy_id', $user->id);
            } else {
                return Helper::jsonErrorResponse('Unauthorized', 403);
            }
        }

        $rooms = $query->paginate($per_page);

        return Helper::jsonResponse(true, 'Chat rooms fetched successfully', 200, $rooms->items(), true, $rooms);
    }

    public function show(int $roomId): JsonResponse
    {
        $user = auth('api')->user();
        $room = $this->getRoomOr404($roomId);

        if (! $this->authorizeParticipant($room, $user)) {
            return Helper::jsonErrorResponse('Unauthorized', 403);
        }

        $threshold = $this->onlineThresholdSeconds();
        $now = Carbon::now();

        $presenceSeeker = ChatPresence::where('chat_room_id', $room->id)
            ->where('user_id', $room->seeker_id)
            ->first();
        $presenceClergy = ChatPresence::where('chat_room_id', $room->id)
            ->where('user_id', $room->clergy_id)
            ->first();

        $seekerOnline = $presenceSeeker && $presenceSeeker->last_seen_at
            ? $now->diffInSeconds($presenceSeeker->last_seen_at) <= $threshold
            : false;

        $clergyOnline = $presenceClergy && $presenceClergy->last_seen_at
            ? $now->diffInSeconds($presenceClergy->last_seen_at) <= $threshold
            : false;

        return Helper::jsonResponse(true, 'Chat room fetched successfully', 200, [
            'room' => $room,
            'participants' => [
                [
                    'user_id' => $room->seeker_id,
                    'role' => 'seeker',
                    'online' => $seekerOnline,
                ],
                [
                    'user_id' => $room->clergy_id,
                    'role' => 'clergy',
                    'online' => $clergyOnline,
                ],
            ],
        ]);
    }

    public function messages(int $roomId): JsonResponse
    {
        $user = auth('api')->user();
        $room = $this->getRoomOr404($roomId);

        if (! $this->authorizeParticipant($room, $user)) {
            return Helper::jsonErrorResponse('Unauthorized', 403);
        }

        $per_page = request()->has('per_page') ? (int) request()->per_page : 15;

        $messages = ChatMessage::where('chat_room_id', $room->id)
            ->with(['sender:id,name,email'])
            ->latest('id')
            ->paginate($per_page);

        return Helper::jsonResponse(true, 'Chat messages fetched successfully', 200, $messages->items(), true, $messages);
    }

    public function sendMessage(SendChatMessageRequest $request, int $roomId): JsonResponse
    {
        $user = auth('api')->user();
        $room = $this->getRoomOr404($roomId);

        if (! $this->authorizeParticipant($room, $user)) {
            return Helper::jsonErrorResponse('Unauthorized', 403);
        }

        // Admin view allowed, but message send restricted to the two participants.
        if ($user->role === 'admin') {
            return Helper::jsonErrorResponse('Admin cannot send chat messages', 403);
        }

        $otherId = $this->otherParticipantId($room, $user->id);
        if (! $otherId) {
            return Helper::jsonErrorResponse('Unauthorized', 403);
        }

        if ($this->isBlocked($room, (int) $user->id, (int) $otherId)) {
            return Helper::jsonErrorResponse('You are blocked in this chat', 403);
        }

        $senderType = $user->role === 'seeker' ? 'seeker' : ($user->role === 'clergy' ? 'clergy' : 'system');

        $chatMessage = ChatMessage::create([
            'chat_room_id' => $room->id,
            'sender_type' => $senderType,
            'sender_id' => $user->id,
            'body' => $request->validated('message'),
            'metadata' => null,
        ]);

        $payload = [
            'id' => $chatMessage->id,
            'chat_room_id' => $chatMessage->chat_room_id,
            'sender_type' => $chatMessage->sender_type,
            'sender_id' => $chatMessage->sender_id,
            'body' => $chatMessage->body,
            'metadata' => $chatMessage->metadata,
            'created_at' => $chatMessage->created_at?->toIso8601String(),
        ];

        event(new MessageEvent($payload, $room->id, $user->id));

        return Helper::jsonResponse(true, 'Message sent successfully', 201, $payload);
    }

    public function presence(ChatPresenceRequest $request, int $roomId): JsonResponse
    {
        $user = auth('api')->user();
        $room = $this->getRoomOr404($roomId);

        if (! $this->authorizeParticipant($room, $user)) {
            return Helper::jsonErrorResponse('Unauthorized', 403);
        }

        ChatPresence::updateOrCreate(
            [
                'chat_room_id' => $room->id,
                'user_id' => $user->id,
            ],
            [
                'last_seen_at' => Carbon::now(),
            ]
        );

        return Helper::jsonResponse(true, 'Presence updated', 200);
    }

    public function block(ChatBlockRequest $request, int $roomId): JsonResponse
    {
        $user = auth('api')->user();
        $room = $this->getRoomOr404($roomId);

        if (! $this->authorizeParticipant($room, $user)) {
            return Helper::jsonErrorResponse('Unauthorized', 403);
        }
        if ($user->role === 'admin') {
            return Helper::jsonErrorResponse('Admin cannot block users', 403);
        }

        $otherId = $this->otherParticipantId($room, $user->id);
        if (! $otherId) {
            return Helper::jsonErrorResponse('Unauthorized', 403);
        }

        $action = $request->input('action', 'block');

        if ($action === 'unblock') {
            ChatBlock::where('chat_room_id', $room->id)
                ->where('blocker_id', $user->id)
                ->where('blocked_id', $otherId)
                ->delete();

            return Helper::jsonResponse(true, 'User unblocked', 200);
        }

        ChatBlock::updateOrCreate(
            [
                'chat_room_id' => $room->id,
                'blocker_id' => $user->id,
                'blocked_id' => $otherId,
            ],
            []
        );

        return Helper::jsonResponse(true, 'User blocked', 200);
    }
}

