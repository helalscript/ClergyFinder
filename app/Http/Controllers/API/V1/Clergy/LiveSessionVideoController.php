<?php

namespace App\Http\Controllers\API\V1\Clergy;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\LiveSessionRequest;
use App\Services\Chat\ChatRoomService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LiveSessionVideoController extends Controller
{
    public function start(int $id, ChatRoomService $chatRoomService): JsonResponse
    {
        $user = auth('api')->user();

        if (! $user->clergy || ! $user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to start live session videos', 403);
        }

        $liveSessionRequest = LiveSessionRequest::find($id);
        if (! $liveSessionRequest) {
            return Helper::jsonResponse(false, 'Live session request not found', 404);
        }

        if ((int) $liveSessionRequest->clergy_id !== (int) $user->id) {
            return Helper::jsonErrorResponse('Unauthorized', 403);
        }

        if ($liveSessionRequest->video_status === 'completed') {
            return Helper::jsonErrorResponse('Video already completed', 409);
        }

        if ($liveSessionRequest->video_status === 'started') {
            return Helper::jsonErrorResponse('Video already started', 409);
        }

        $now = Carbon::now();

        $liveSessionRequest->update([
            'video_status' => 'started',
            'video_started_at' => $now,
        ]);

        $room = ChatRoom::where('live_session_request_id', $liveSessionRequest->id)->first();
        if (! $room) {
            $room = $chatRoomService->createLiveSessionChatRoom($liveSessionRequest->fresh());
        }

        $room->update([
            'video_status' => 'started',
            'video_started_at' => $now,
        ]);

        $chatRoomService->addSystemMessage($room, 'Video session started.', [
            'video_started_at' => $now->toIso8601String(),
        ]);

        return Helper::jsonResponse(true, 'Video started successfully', 200, [
            'live_session_request_id' => $liveSessionRequest->id,
            'chat_room_id' => $room->id,
            'video_status' => 'started',
        ]);
    }

    public function complete(int $id, ChatRoomService $chatRoomService): JsonResponse
    {
        $user = auth('api')->user();

        if (! $user->clergy || ! $user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to complete live session videos', 403);
        }

        $liveSessionRequest = LiveSessionRequest::find($id);
        if (! $liveSessionRequest) {
            return Helper::jsonResponse(false, 'Live session request not found', 404);
        }

        if ((int) $liveSessionRequest->clergy_id !== (int) $user->id) {
            return Helper::jsonErrorResponse('Unauthorized', 403);
        }

        if ($liveSessionRequest->video_status !== 'started') {
            return Helper::jsonErrorResponse('Video not started yet', 409);
        }

        $now = Carbon::now();

        $liveSessionRequest->update([
            'video_status' => 'completed',
            'video_completed_at' => $now,
            'status' => 'completed',
        ]);

        $room = ChatRoom::where('live_session_request_id', $liveSessionRequest->id)->first();
        if (! $room) {
            $room = $chatRoomService->createLiveSessionChatRoom($liveSessionRequest->fresh());
        }

        $room->update([
            'status' => 'completed',
            'video_status' => 'completed',
            'video_completed_at' => $now,
        ]);

        $chatRoomService->addSystemMessage($room, 'Video session completed.', [
            'video_completed_at' => $now->toIso8601String(),
        ]);

        return Helper::jsonResponse(true, 'Video completed successfully', 200, [
            'live_session_request_id' => $liveSessionRequest->id,
            'chat_room_id' => $room->id,
            'video_status' => 'completed',
        ]);
    }
}

