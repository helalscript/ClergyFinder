<?php

namespace App\Http\Controllers\API\V1\Clergy;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReplyPrayerRequestRequest;
use App\Mail\PrayerReplyToSeeker;
use App\Models\PrayerRequest;
use App\Notifications\Chat\PrayerRepliedToSeekerInApp;
use App\Services\Chat\ChatRoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class PrayerRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $per_page = request()->has('per_page') ? request()->per_page : 15;
        $user = auth('api')->user();
        if (!$user->clergy || !$user->clergy->is_prayer_request) {
            return Helper::jsonErrorResponse('You are not allowed to receive prayer requests', 403);
        }

        $requests = PrayerRequest::pending()
            ->with(['seeker:id,name,email,avatar'])
            ->latest()
            ->paginate($per_page);

        return Helper::jsonResponse(true, 'Prayer requests fetched successfully', 200, $requests, true);
    }

    public function show(int $id): JsonResponse
    {
        $user = auth('api')->user();
        if (!$user->clergy || !$user->clergy->is_prayer_request) {
            return Helper::jsonErrorResponse('You are not allowed to receive prayer requests', 403);
        }

        $request = PrayerRequest::with(['seeker:id,name,email'])
        ->where('clergy_id', $user->clergy->id)
        ->find($id);

        if (!$request) {
            return Helper::jsonResponse(false, 'Prayer request not found', 404);
        }

        return Helper::jsonResponse(true, 'Prayer request fetched successfully', 200, $request);
    }

    public function reply(ReplyPrayerRequestRequest $request, int $id, ChatRoomService $chatRoomService): JsonResponse
    {
        $user = auth('api')->user();
        if (!$user->clergy || !$user->clergy->is_prayer_request) {
            return Helper::jsonErrorResponse('You are not allowed to reply to prayer requests', 403);
        }

        $prayerRequest = PrayerRequest::pending()->find($id);

        if (!$prayerRequest) {
            return Helper::jsonResponse(false, 'Prayer request not found or already replied', 404);
        }

        $prayerRequest->update([
            'clergy_id' => $user->id,
            'clergy_reply' => $request->validated('reply_message'),
            'status' => 'replied',
        ]);

        $prayerRequest->load(['seeker']);
        Mail::to($prayerRequest->seeker->email)->send(new PrayerReplyToSeeker($prayerRequest));

        $freshRequest = $prayerRequest->fresh();
        $chatRoom = $chatRoomService->createPrayerChatRoom($freshRequest);
        $prayerRequest->seeker->notify(new PrayerRepliedToSeekerInApp($freshRequest, $chatRoom));

        $freshRequest->load(['seeker:id,name,email']);

        $freshRequest->setAttribute('chat_room_id', $chatRoom->id);

        return Helper::jsonResponse(true, 'Reply sent successfully', 200, $freshRequest);
    }
}
