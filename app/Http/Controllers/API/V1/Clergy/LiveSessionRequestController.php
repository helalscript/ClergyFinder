<?php

namespace App\Http\Controllers\API\V1\Clergy;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ScheduleLiveSessionRequestRequest;
use App\Mail\LiveSessionScheduledToSeeker;
use App\Models\LiveSessionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class LiveSessionRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $per_page = request()->has('per_page') ? request()->per_page : 15;
        $user = auth('api')->user();
        if (!$user->clergy || !$user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to receive live session requests', 403);
        }

        $requests = LiveSessionRequest::with(['seeker:id,name,email'])
            ->latest()
            ->paginate($per_page);

        return Helper::jsonResponse(true, 'Live session requests fetched successfully', 200, $requests, true);
    }

    public function show(int $id): JsonResponse
    {
        $user = auth('api')->user();
        if (!$user->clergy || !$user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to receive live session requests', 403);
        }

        $request = LiveSessionRequest::with(['seeker:id,name,email'])->find($id);

        if (!$request) {
            return Helper::jsonResponse(false, 'Live session request not found', 404);
        }

        return Helper::jsonResponse(true, 'Live session request fetched successfully', 200, $request);
    }

    public function schedule(ScheduleLiveSessionRequestRequest $request, int $id): JsonResponse
    {
        $user = auth('api')->user();
        if (!$user->clergy || !$user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to schedule live sessions', 403);
        }

        $liveSessionRequest = LiveSessionRequest::where('status', 'pending')->find($id);

        if (!$liveSessionRequest) {
            return Helper::jsonResponse(false, 'Live session request not found or already scheduled', 404);
        }

        $liveSessionRequest->update([
            'clergy_id' => $user->id,
            'meeting_link' => $request->validated('meeting_link'),
            'scheduled_time' => $request->validated('scheduled_time'),
            'status' => 'scheduled',
        ]);

        $liveSessionRequest->load(['seeker']);
        Mail::to($liveSessionRequest->seeker->email)->send(new LiveSessionScheduledToSeeker($liveSessionRequest));

        return Helper::jsonResponse(true, 'Live session scheduled successfully', 200, $liveSessionRequest->fresh(['seeker:id,name,email']));
    }
}
