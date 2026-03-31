<?php

namespace App\Http\Controllers\API\V1\Seeker;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreLiveSessionRequestRequest;
use App\Mail\NewLiveSessionRequestToClergy;
use App\Models\Clergy;
use App\Models\LiveSessionRequest;
use App\Notifications\Chat\NewLiveSessionRequestToClergyInApp;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LiveSessionRequestController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $per_page = request()->has('per_page') ? request()->per_page : 15;

            $requests = LiveSessionRequest::where('seeker_id', auth('api')->id())
                ->with(['clergy:id,name,email'])
                ->latest()
                ->paginate($per_page);

            return Helper::jsonResponse(true, 'Live session requests fetched successfully', 200, $requests, true);
        } catch (Exception $e) {
            Log::error('LiveSessionRequestController::index '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to fetch live session requests', 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $request = LiveSessionRequest::where('seeker_id', auth('api')->id())
                ->with(['clergy:id,name,email'])
                ->find($id);

            if (! $request) {
                return Helper::jsonResponse(false, 'Live session request not found', 404);
            }

            return Helper::jsonResponse(true, 'Live session request fetched successfully', 200, $request);
        } catch (Exception $e) {
            Log::error('LiveSessionRequestController::show '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to fetch live session request', 500);
        }
    }

    public function store(StoreLiveSessionRequestRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $clergy = Clergy::with('user:id,name,email')->find($request->validated('clergy_id'));
            if (! $clergy) {
                return Helper::jsonErrorResponse('Clergy not found', 404);
            }
            $liveSessionRequest = LiveSessionRequest::create([
                'clergy_id' => $request->validated('clergy_id'),
                'clergy_user_id' => $clergy->user_id,
                'seeker_id' => auth('api')->id(),
                'message' => $request->validated('message'),
                'status' => 'pending',
            ]);

            if ($clergy) {
                Mail::to($clergy->user->email)->queue(new NewLiveSessionRequestToClergy($liveSessionRequest->fresh()));
                $clergy->user->notify(new NewLiveSessionRequestToClergyInApp($liveSessionRequest->fresh()));
            } else {
                return Helper::jsonResponse(false, 'Clergy not found', 404);
            }

            DB::commit();
            return Helper::jsonResponse(true, 'Live session request submitted successfully', 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('LiveSessionRequestController::store '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to submit live session request', 500);
        }
    }
}
