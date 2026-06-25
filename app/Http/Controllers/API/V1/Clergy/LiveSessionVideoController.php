<?php

namespace App\Http\Controllers\API\V1\Clergy;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\LiveSessionRequest;
use App\Notifications\Chat\LiveSessionClergyStartedVideoInApp;
use App\Services\Chat\ChatRoomService;
use App\Services\LiveSessionQueueService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LiveSessionVideoController extends Controller
{
    public function start($id, ChatRoomService $chatRoomService): JsonResponse
    {
        $user = auth('api')->user();

        if (! $user->clergy || ! $user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to start live session videos', 403);
        }

        $liveSessionRequest = LiveSessionRequest::where('clergy_id', $user->clergy->id)->find($id);
        if (! $liveSessionRequest) {
            return Helper::jsonResponse(false, 'Live session request not found', 404);
        }

        if ((int) $liveSessionRequest->clergy_user_id !== (int) $user->id) {
            return Helper::jsonErrorResponse('Unauthorized', 403);
        }

        if ($liveSessionRequest->status !== 'ready') {
            return Helper::jsonErrorResponse('The seeker must confirm they are ready before you can start the call.', 409);
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
            'status' => 'active',
        ]);

        $freshRequest = $liveSessionRequest->fresh();
        if ($freshRequest->seeker) {
            $freshRequest->seeker->notify(new LiveSessionClergyStartedVideoInApp($freshRequest));
        }

        $room = ChatRoom::where('live_session_request_id', $liveSessionRequest->id)->first();
        if ($room) {
            $room->update([
                'video_status' => 'started',
                'video_started_at' => $now,
            ]);

            try {
                $chatRoomService->addSystemMessage($room, 'Video session started.', [
                    'video_started_at' => $now->toIso8601String(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('LiveSessionVideoController::start system message '.$e->getMessage());
            }
        }

        return Helper::jsonResponse(true, 'Video started successfully', 200, [
            'live_session_request_id' => $liveSessionRequest->id,
            'chat_room_id' => $room?->id,
            'agora_channel' => $liveSessionRequest->fresh()->agora_channel,
            'video_status' => 'started',
        ]);
    }

    public function complete($id, ChatRoomService $chatRoomService, LiveSessionQueueService $queueService): JsonResponse
    {
        $user = auth('api')->user();

        if (! $user->clergy || ! $user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to complete live session videos', 403);
        }

        $liveSessionRequest = LiveSessionRequest::where('clergy_id', $user->clergy->id)->find($id);
        if (! $liveSessionRequest) {
            return Helper::jsonResponse(false, 'Live session request not found', 404);
        }

        if ((int) $liveSessionRequest->clergy_user_id !== (int) $user->id) {
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
        if ($room) {
            $room->update([
                'status' => 'completed',
                'video_status' => 'completed',
                'video_completed_at' => $now,
            ]);

            try {
                $chatRoomService->addSystemMessage($room, 'Video session completed.', [
                    'video_completed_at' => $now->toIso8601String(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('LiveSessionVideoController::complete system message '.$e->getMessage());
            }
        }

        $queueService->onVideoCompleted($liveSessionRequest->fresh());

        return Helper::jsonResponse(true, 'Video completed successfully', 200, [
            'live_session_request_id' => $liveSessionRequest->id,
            'chat_room_id' => $room?->id,
            'video_status' => 'completed',
        ]);
    }
}
