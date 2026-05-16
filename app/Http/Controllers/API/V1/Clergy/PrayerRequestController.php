<?php

namespace App\Http\Controllers\API\V1\Clergy;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DeclinePrayerRequestRequest;
use App\Models\PrayerRequest;
use App\Services\PrayerRequestFlowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PrayerRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $per_page = request()->has('per_page') ? request()->per_page : 15;
        $user = auth('api')->user();
        if (! $user->clergy || ! $user->clergy->is_prayer_request) {
            return Helper::jsonErrorResponse('You are not allowed to receive prayer requests', 403);
        }

        $tab = request()->query('tab', 'pending');

        $query = PrayerRequest::query()
            ->where('clergy_id', $user->clergy->id)
            ->with(['seeker:id,name,email,avatar']);

        if ($tab === 'pending') {
            $query->pendingClergy();
        } elseif ($tab === 'active') {
            $query->activeChat();
        } elseif ($tab === 'history') {
            $query->whereIn('status', ['closed', 'declined']);
        }

        $requests = $query->latest()->paginate($per_page);

        return Helper::jsonResponse(true, 'Prayer requests fetched successfully', 200, $requests, true);
    }

    public function show(int $id): JsonResponse
    {
        $user = auth('api')->user();
        if (! $user->clergy || ! $user->clergy->is_prayer_request) {
            return Helper::jsonErrorResponse('You are not allowed to receive prayer requests', 403);
        }

        $request = PrayerRequest::with(['seeker:id,name,email', 'chatRoom:id,prayer_request_id,status'])
            ->where('clergy_id', $user->clergy->id)
            ->find($id);

        if (! $request) {
            return Helper::jsonResponse(false, 'Prayer request not found', 404);
        }

        return Helper::jsonResponse(true, 'Prayer request fetched successfully', 200, $request);
    }

    public function accept(int $id, PrayerRequestFlowService $flowService): JsonResponse
    {
        $user = auth('api')->user();
        if (! $user->clergy || ! $user->clergy->is_prayer_request) {
            return Helper::jsonErrorResponse('You are not allowed to accept prayer requests', 403);
        }

        $prayerRequest = PrayerRequest::where('clergy_id', $user->clergy->id)->find($id);
        if (! $prayerRequest) {
            return Helper::jsonResponse(false, 'Prayer request not found', 404);
        }

        try {
            $fresh = $flowService->accept($user, $prayerRequest);

            return Helper::jsonResponse(true, 'Prayer request accepted', 200, $fresh);
        } catch (\RuntimeException $e) {
            $code = match ($e->getMessage()) {
                'invalid_status' => 409,
                'forbidden' => 403,
                'not_allowed' => 403,
                default => 400,
            };

            return Helper::jsonErrorResponse($e->getMessage(), $code);
        } catch (\Throwable $e) {
            Log::error('Clergy\\PrayerRequestController::accept '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to accept prayer request', 500);
        }
    }

    public function decline(DeclinePrayerRequestRequest $request, int $id, PrayerRequestFlowService $flowService): JsonResponse
    {
        $user = auth('api')->user();
        if (! $user->clergy || ! $user->clergy->is_prayer_request) {
            return Helper::jsonErrorResponse('You are not allowed to decline prayer requests', 403);
        }

        $prayerRequest = PrayerRequest::where('clergy_id', $user->clergy->id)->find($id);
        if (! $prayerRequest) {
            return Helper::jsonResponse(false, 'Prayer request not found', 404);
        }

        try {
            $fresh = $flowService->decline($user, $prayerRequest, $request->validated('reason'));

            return Helper::jsonResponse(true, 'Prayer request declined', 200, $fresh);
        } catch (\RuntimeException $e) {
            $code = match ($e->getMessage()) {
                'invalid_status' => 409,
                'forbidden' => 403,
                'not_allowed' => 403,
                default => 400,
            };

            return Helper::jsonErrorResponse($e->getMessage(), $code);
        } catch (\Throwable $e) {
            Log::error('Clergy\\PrayerRequestController::decline '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to decline prayer request', 500);
        }
    }

    public function close(int $id, PrayerRequestFlowService $flowService): JsonResponse
    {
        $user = auth('api')->user();
        if (! $user->clergy || ! $user->clergy->is_prayer_request) {
            return Helper::jsonErrorResponse('You are not allowed to close prayer threads', 403);
        }

        $prayerRequest = PrayerRequest::where('clergy_id', $user->clergy->id)->find($id);
        if (! $prayerRequest) {
            return Helper::jsonResponse(false, 'Prayer request not found', 404);
        }

        try {
            $fresh = $flowService->close($user, $prayerRequest);

            return Helper::jsonResponse(true, 'Prayer thread closed', 200, $fresh);
        } catch (\RuntimeException $e) {
            $code = match ($e->getMessage()) {
                'invalid_status' => 409,
                'forbidden' => 403,
                'not_allowed' => 403,
                default => 400,
            };

            return Helper::jsonErrorResponse($e->getMessage(), $code);
        } catch (\Throwable $e) {
            Log::error('Clergy\\PrayerRequestController::close '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to close prayer thread', 500);
        }
    }
}
