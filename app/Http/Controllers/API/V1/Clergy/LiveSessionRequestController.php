<?php

namespace App\Http\Controllers\API\V1\Clergy;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LiveAvailabilityRequest;
use App\Models\LiveSessionRequest;
use App\Notifications\Chat\ClergyLiveStepOutToSeekerInApp;
use App\Services\LiveSessionQueueService;
use Illuminate\Http\JsonResponse;

class LiveSessionRequestController extends Controller
{
    public function index(LiveSessionQueueService $queueService): JsonResponse
    {
        $user = auth('api')->user();
        if (! $user->clergy || ! $user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to manage live sessions', 403);
        }

        $tab = request()->query('tab', 'queue');
        $per_page = request()->has('per_page') ? request()->per_page : 15;

        if ($tab === 'queue') {
            $snapshot = $queueService->queueSnapshotForClergy($user->clergy);

            return Helper::jsonResponse(true, 'Live session queue snapshot', 200, $snapshot);
        }

        $requests = LiveSessionRequest::where('clergy_id', $user->clergy->id)
            ->with(['seeker:id,name,email,avatar'])
            ->latest()
            ->paginate($per_page);

        return Helper::jsonResponse(true, 'Live session history fetched successfully', 200, $requests, true);
    }

    public function show(int $id): JsonResponse
    {
        $user = auth('api')->user();
        if (! $user->clergy || ! $user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to view live sessions', 403);
        }

        $request = LiveSessionRequest::with(['seeker:id,name,email,avatar'])
            ->where('clergy_id', $user->clergy->id)
            ->find($id);

        if (! $request) {
            return Helper::jsonResponse(false, 'Live session record not found', 404);
        }

        return Helper::jsonResponse(true, 'Live session record fetched successfully', 200, $request);
    }

    public function updateLiveAvailability(LiveAvailabilityRequest $request): JsonResponse
    {
        $user = auth('api')->user();
        if (! $user->clergy || ! $user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to update availability', 403);
        }

        $clergy = $user->clergy;
        $wasAvailable = (bool) $clergy->is_available_for_live;

        $clergy->update([
            'is_available_for_live' => $request->boolean('is_available_for_live'),
            'live_unavailable_message' => $request->input('live_unavailable_message'),
        ]);

        if ($wasAvailable && ! $clergy->is_available_for_live) {
            $waiting = LiveSessionRequest::query()
                ->where('clergy_id', $clergy->id)
                ->whereIn('status', ['queued', 'awaiting_confirm', 'ready'])
                ->with('seeker')
                ->get();

            foreach ($waiting as $row) {
                if ($row->seeker) {
                    $row->seeker->notify(new ClergyLiveStepOutToSeekerInApp(
                        $clergy->fresh(),
                        $request->input('live_unavailable_message')
                    ));
                }
            }
        }

        if ($clergy->is_available_for_live) {
            $clergy->update(['live_unavailable_message' => null]);
        }

        return Helper::jsonResponse(true, 'Live availability updated', 200, $clergy->fresh());
    }

    public function notifyNextSeeker(LiveSessionQueueService $queueService): JsonResponse
    {
        $user = auth('api')->user();
        if (! $user->clergy || ! $user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to manage live sessions', 403);
        }

        if (! $user->clergy->is_available_for_live) {
            return Helper::jsonErrorResponse('Turn on live availability before notifying seekers.', 422);
        }

        try {
            $result = $queueService->notifyNextSeeker($user->clergy);

            $message = $result['action'] === 'reminded'
                ? 'Reminder sent to the seeker waiting to confirm.'
                : 'Next seeker has been notified to confirm they are ready.';

            return Helper::jsonResponse(true, $message, 200, $result);
        } catch (\RuntimeException $e) {
            $msg = match ($e->getMessage()) {
                'session_in_progress' => 'A seeker is already ready or in a live call. Start or complete it first.',
                'queue_empty' => 'No seekers are waiting in your queue.',
                default => $e->getMessage(),
            };
            $code = $e->getMessage() === 'queue_empty' ? 404 : 409;

            return Helper::jsonErrorResponse($msg, $code);
        }
    }

    public function reject(int $id, \Illuminate\Http\Request $request, LiveSessionQueueService $queueService): JsonResponse
    {
        $user = auth('api')->user();
        if (! $user->clergy || ! $user->clergy->is_live_session) {
            return Helper::jsonErrorResponse('You are not allowed to manage live sessions', 403);
        }

        $liveSessionRequest = LiveSessionRequest::where('clergy_id', $user->clergy->id)->find($id);
        if (! $liveSessionRequest) {
            return Helper::jsonResponse(false, 'Live session record not found', 404);
        }

        if (! in_array($liveSessionRequest->status, ['queued', 'awaiting_confirm', 'ready', 'active'], true)) {
            return Helper::jsonErrorResponse('This live session cannot be rejected in its current status.', 409);
        }

        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $reason = $request->input('reason', 'Session cancelled by clergy.');

        $queueService->rejectSessionByClergy($liveSessionRequest, $reason);

        return Helper::jsonResponse(true, 'Live session request rejected/removed successfully.', 200, [
            'live_session_request_id' => $liveSessionRequest->id,
            'status' => $liveSessionRequest->fresh()->status,
        ]);
    }
}
