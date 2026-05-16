<?php

namespace App\Http\Controllers\API\V1\Seeker;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JoinLiveSessionQueueRequest;
use App\Models\Clergy;
use App\Models\LiveSessionRequest;
use App\Services\LiveSessionQueueService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LiveSessionRequestController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $per_page = request()->has('per_page') ? request()->per_page : 15;

            $requests = LiveSessionRequest::where('seeker_id', auth('api')->id())
                ->with(['clergyProfile:id,user_id,religion', 'clergyProfile.user:id,name,email'])
                ->latest()
                ->paginate($per_page);

            return Helper::jsonResponse(true, 'Live session records fetched successfully', 200, $requests, true);
        } catch (Exception $e) {
            Log::error('LiveSessionRequestController::index '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to fetch live session records', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $request = LiveSessionRequest::where('seeker_id', auth('api')->id())
                ->with(['clergyProfile:id,user_id,religion', 'clergyProfile.user:id,name,email'])
                ->find($id);

            if (! $request) {
                return Helper::jsonResponse(false, 'Live session record not found', 404);
            }

            return Helper::jsonResponse(true, 'Live session record fetched successfully', 200, $request);
        } catch (Exception $e) {
            Log::error('LiveSessionRequestController::show '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to fetch live session record', 500);
        }
    }

    public function joinQueue(JoinLiveSessionQueueRequest $request, LiveSessionQueueService $queueService): JsonResponse
    {
        try {
            $clergy = Clergy::with('user')->find($request->validated('clergy_id'));
            if (! $clergy) {
                return Helper::jsonErrorResponse('Clergy not found', 404);
            }

            [$row, $position] = $queueService->joinQueue(
                auth('api')->user(),
                $clergy,
                $request->validated('message')
            );

            $row->load(['clergyProfile.user:id,name,email']);

            return Helper::jsonResponse(true, 'Joined live session queue', 201, [
                'live_session_request' => $row,
                'queue_position' => $position,
                'heartbeat_interval_seconds' => max(5, (int) (LiveSessionQueueService::HEARTBEAT_TIMEOUT_SECONDS / 3)),
            ]);
        } catch (\RuntimeException $e) {
            $msg = match ($e->getMessage()) {
                'already_in_queue' => 'You are already in a live session queue.',
                'clergy_unavailable' => 'This clergy member is not available for live sessions.',
                'clergy_not_accepting_live' => 'This clergy member is not online for live sessions right now.',
                default => $e->getMessage(),
            };
            $code = $e->getMessage() === 'already_in_queue' ? 409 : 422;

            return Helper::jsonErrorResponse($msg, $code);
        } catch (Exception $e) {
            Log::error('LiveSessionRequestController::joinQueue '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to join queue', 500);
        }
    }

    public function heartbeat(int $id, LiveSessionQueueService $queueService): JsonResponse
    {
        try {
            $req = LiveSessionRequest::where('seeker_id', auth('api')->id())->find($id);
            if (! $req) {
                return Helper::jsonResponse(false, 'Live session record not found', 404);
            }

            $queueService->heartbeat(auth('api')->user(), $req);

            return Helper::jsonResponse(true, 'Heartbeat recorded', 200, [
                'live_session_request_id' => $req->id,
                'last_queue_heartbeat_at' => $req->fresh()->last_queue_heartbeat_at,
            ]);
        } catch (\RuntimeException $e) {
            return Helper::jsonErrorResponse('Unable to update heartbeat', 403);
        } catch (Exception $e) {
            Log::error('LiveSessionRequestController::heartbeat '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed heartbeat', 500);
        }
    }

    public function leaveQueue(int $id, LiveSessionQueueService $queueService): JsonResponse
    {
        try {
            $req = LiveSessionRequest::where('seeker_id', auth('api')->id())->find($id);
            if (! $req) {
                return Helper::jsonResponse(false, 'Live session record not found', 404);
            }

            $queueService->leaveQueue(auth('api')->user(), $req);

            return Helper::jsonResponse(true, 'Left live session queue', 200);
        } catch (\RuntimeException $e) {
            return Helper::jsonErrorResponse('Unable to leave queue for this record', 403);
        } catch (Exception $e) {
            Log::error('LiveSessionRequestController::leaveQueue '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to leave queue', 500);
        }
    }

    public function confirmReady(int $id, LiveSessionQueueService $queueService): JsonResponse
    {
        try {
            $req = LiveSessionRequest::where('seeker_id', auth('api')->id())->find($id);
            if (! $req) {
                return Helper::jsonResponse(false, 'Live session record not found', 404);
            }

            $queueService->confirmReady(auth('api')->user(), $req);

            $fresh = $req->fresh();

            return Helper::jsonResponse(true, 'Ready confirmed. The clergy has been notified. Waiting for them to start the video call.', 200, [
                'live_session_request' => $fresh,
                'status' => $fresh->status,
                'agora_channel' => $fresh->agora_channel,
                'ready_confirmed_at' => $fresh->ready_confirmed_at,
            ]);
        } catch (\RuntimeException $e) {
            $msg = $e->getMessage() === 'expired'
                ? 'The confirmation window has expired.'
                : 'Unable to confirm for this record.';

            return Helper::jsonErrorResponse($msg, 409);
        } catch (Exception $e) {
            Log::error('LiveSessionRequestController::confirmReady '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to confirm ready', 500);
        }
    }
}
