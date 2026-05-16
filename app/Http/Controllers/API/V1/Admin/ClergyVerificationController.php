<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\ClergyRegistrationRejected;
use App\Models\Clergy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ClergyVerificationController extends Controller
{
    public function pending(Request $request): JsonResponse
    {
        $perPage = (int) ($request->input('per_page', 15));

        $q = Clergy::query()
            ->where('registration_status', 'pending_admin')
            ->with('user:id,name,email,phone,avatar')
            ->latest();

        $paginator = $q->paginate($perPage);

        return Helper::jsonResponse(true, 'Pending clergy registrations', 200, $paginator->items(), true, $paginator);
    }

    public function approve(int $id): JsonResponse
    {
        $clergy = Clergy::with('user')->find($id);
        if (! $clergy) {
            return Helper::jsonResponse(false, 'Clergy record not found', 404);
        }

        $clergy->update([
            'registration_status' => 'approved',
            'registration_rejection_reason' => null,
            'registration_reviewed_at' => now(),
            'is_verified' => true,
        ]);

        return Helper::jsonResponse(true, 'Clergy registration approved', 200, $clergy->fresh()->load('user:id,name,email'));
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'reason' => 'required|string|max:2000',
        ]);

        $clergy = Clergy::with('user')->find($id);
        if (! $clergy || ! $clergy->user) {
            return Helper::jsonResponse(false, 'Clergy record not found', 404);
        }

        $clergy->update([
            'registration_status' => 'rejected',
            'registration_rejection_reason' => $data['reason'],
            'registration_reviewed_at' => now(),
        ]);

        Mail::to($clergy->user->email)->queue(new ClergyRegistrationRejected($clergy->user, $data['reason']));

        return Helper::jsonResponse(true, 'Clergy registration rejected', 200, $clergy->fresh()->load('user:id,name,email'));
    }
}
