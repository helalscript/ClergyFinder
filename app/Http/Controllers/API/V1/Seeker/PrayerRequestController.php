<?php

namespace App\Http\Controllers\API\V1\Seeker;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePrayerRequestRequest;
use App\Mail\NewPrayerRequestToClergy;
use App\Notifications\Chat\NewPrayerRequestToClergyInApp;
use App\Models\Clergy;
use App\Models\PrayerRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PrayerRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $requests = PrayerRequest::where('seeker_id', auth('api')->id())
            ->with(['clergyUser:id,name,email'])
            ->latest()
            ->paginate(15);

        return Helper::jsonResponse(true, 'Prayer requests fetched successfully', 200, $requests, true);
    }

    public function show(int $id): JsonResponse
    {
        $request = PrayerRequest::where('seeker_id', auth('api')->id())->with(['clergyUser:id,name,email'])->find($id);

        if (! $request) {
            return Helper::jsonResponse(false, 'Prayer request not found', 404);
        }

        return Helper::jsonResponse(true, 'Prayer request fetched successfully', 200, $request);
    }

    public function store(StorePrayerRequestRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $clergy = Clergy::find($request->validated('clergy_id'));
            if (! $clergy) {
                return Helper::jsonErrorResponse('Clergy not found', 404);
            }
            if ($clergy->registration_status !== 'approved' || $clergy->status !== 'active' || ! $clergy->is_prayer_request) {
                return Helper::jsonErrorResponse('This clergy member is not accepting prayer requests.', 422);
            }

            $prayerRequest = PrayerRequest::create([
                'clergy_id' => $clergy->id,
                'clergy_user_id' => $clergy->user_id,
                'seeker_id' => auth('api')->id(),
                'message' => $request->validated('message'),
                'status' => 'pending_clergy',
            ]);

            $clergyUsers = Clergy::with('user:id,name,email')->find($request->validated('clergy_id'));
            if ($clergyUsers) {
                // Mail::to('husoaib422@gmail.com')->queue(new NewPrayerRequestToClergy($prayerRequest->fresh()));
                Mail::to($clergyUsers->user->email)->queue(new NewPrayerRequestToClergy($prayerRequest->fresh()));
                $clergyUsers->user->notify(new NewPrayerRequestToClergyInApp($prayerRequest->fresh()));
            }
            DB::commit();

            return Helper::jsonResponse(true, 'Prayer request submitted successfully', 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PrayerRequestController::store '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to submit prayer request', 500);
        }
    }
}