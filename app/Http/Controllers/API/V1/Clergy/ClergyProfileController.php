<?php

namespace App\Http\Controllers\API\V1\Clergy;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReapplyClergyRegistrationRequest;
use App\Http\Requests\Api\UpdateClergyProfileRequest;
use App\Models\Clergy;
use App\Models\User;
use App\Services\Clergy\ClergyProfileService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClergyProfileController extends Controller
{
    public function __construct(
        private ClergyProfileService $profileService
    ) {}

    public function show(): JsonResponse
    {
        $user = $this->clergyUser();

        $data = User::with('clergy')
            ->select('id', 'name', 'first_name', 'last_name', 'email', 'phone', 'avatar', 'cover_photo', 'gender', 'role', 'status')
            ->find($user->id);

        return Helper::jsonResponse(true, 'Clergy profile fetched successfully', 200, $data);
    }

    public function registrationStatus(): JsonResponse
    {
        $user = $this->clergyUser();
        $clergy = $user->clergy;

        if (! $clergy) {
            return Helper::jsonErrorResponse('Clergy profile not found.', 404);
        }

        return Helper::jsonResponse(true, 'Clergy registration status', 200, [
            'registration_status' => $clergy->registration_status,
            'registration_rejection_reason' => $clergy->registration_rejection_reason,
            'registration_reviewed_at' => $clergy->registration_reviewed_at,
            'can_reapply' => $clergy->registration_status === 'rejected',
            'is_verified' => (bool) $clergy->is_verified,
        ]);
    }

    public function reapply(ReapplyClergyRegistrationRequest $request): JsonResponse
    {
        $user = $this->clergyUser();
        $clergy = $user->clergy;
        if (! $clergy) {
            return Helper::jsonErrorResponse('Clergy profile not found.', 404);
        }

        if ($clergy->registration_status === 'approved') {
            return Helper::jsonErrorResponse('Your clergy profile is already approved.', 409);
        }

        if ($clergy->registration_status === 'pending_admin') {
            return Helper::jsonErrorResponse('Your application is already under review.', 409);
        }

        if ($clergy->registration_status !== 'rejected') {
            return Helper::jsonErrorResponse('You can only re-apply after a rejected registration.', 409);
        }

        try {
            DB::beginTransaction();

            if ($request->filled('name')) {
                $user->update(['name' => $request->input('name')]);
            }

            if ($request->has('phone')) {
                $user->update(['phone' => $request->input('phone')]);
            }

            try {
                $credentialPaths = $this->profileService->uploadCredentialDocuments(
                    $request,
                    $clergy->getCredentialDocumentStoragePaths()
                );
            } catch (\RuntimeException $e) {
                DB::rollBack();

                if ($e->getMessage() === 'no_credential_files') {
                    return Helper::jsonErrorResponse('Please upload at least one valid credential document.', 422);
                }

                throw $e;
            }

            $clergy->update([
                'religion' => $request->input('religion', $clergy->religion),
                'denomination' => $request->input('denomination', $clergy->denomination),
                'bio' => $request->input('bio', $clergy->bio),
                'address' => $request->input('address', $clergy->address),
                'city' => $request->input('city', $clergy->city),
                'state' => $request->input('state', $clergy->state),
                'zip_code' => $request->input('zip_code', $clergy->zip_code),
                'country' => $request->input('country', $clergy->country),
                'preferred_language' => $this->profileService->normalizeStringOrArrayField($request->input('preferred_language'))
                    ?? $clergy->preferred_language,
                'specialities' => $this->profileService->normalizeStringOrArrayField($request->input('specialities'))
                    ?? $clergy->specialities,
                'credential_documents' => $credentialPaths,
                'registration_status' => 'pending_admin',
                'registration_rejection_reason' => null,
                'registration_reviewed_at' => null,
                'is_verified' => false,
            ]);

            DB::commit();

            return Helper::jsonResponse(
                true,
                'Your clergy registration has been re-submitted. An admin will review your application again.',
                200,
                $user->fresh()->load('clergy')
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ClergyProfileController::reapply '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to re-submit registration. Please try again.', 500);
        }
    }

    public function update(UpdateClergyProfileRequest $request): JsonResponse
    {
        $user = $this->clergyUser();
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $preferredLanguage = $this->profileService->normalizeStringOrArrayField($request->input('preferred_language'));
            $specialities = $this->profileService->normalizeStringOrArrayField($request->input('specialities'));

            if ($request->hasFile('avatar')) {
                if (! empty($user->avatar)) {
                    Helper::fileDelete(public_path($user->getRawOriginal('avatar')));
                }
                $validated['avatar'] = Helper::fileUpload(
                    $request->file('avatar'),
                    'user/avatar',
                    getFileName($request->file('avatar'))
                );
            } else {
                unset($validated['avatar']);
            }

            if ($request->hasFile('cover_photo')) {
                if (! empty($user->cover_photo)) {
                    Helper::fileDelete(public_path($user->getRawOriginal('cover_photo')));
                }
                $validated['cover_photo'] = Helper::fileUpload(
                    $request->file('cover_photo'),
                    'user/cover_photo',
                    getFileName($request->file('cover_photo'))
                );
            } else {
                unset($validated['cover_photo']);
            }

            $validated['name'] = trim(($validated['first_name'] ?? '').' '.($validated['last_name'] ?? ''));

            $user->update([
                'name' => $validated['name'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'] ?? $user->phone,
                'gender' => $validated['gender'] ?? $user->gender,
                ...array_intersect_key($validated, array_flip(['avatar', 'cover_photo'])),
            ]);

            $clergyPayload = [
                'religion' => $validated['religion'] ?? null,
                'denomination' => $validated['denomination'] ?? null,
                'preferred_language' => $preferredLanguage,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'zip_code' => $validated['zip_code'] ?? null,
                'country' => $validated['country'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'available_at' => $validated['available_at'] ?? null,
                'specialities' => $specialities,
                'is_live_session' => (bool) $request->input('is_live_session', true),
                'is_prayer_request' => (bool) $request->input('is_prayer_request', true),
            ];

            $existingClergy = $user->clergy;
            if ($existingClergy && ($existingClergy->registration_status ?? 'approved') === 'approved') {
                if ($request->has('is_available_for_live')) {
                    $clergyPayload['is_available_for_live'] = $request->boolean('is_available_for_live');
                }
                if ($request->has('live_unavailable_message')) {
                    $clergyPayload['live_unavailable_message'] = $request->input('live_unavailable_message');
                }
            }

            Clergy::updateOrCreate(['user_id' => $user->id], $clergyPayload);

            DB::commit();

            $fresh = User::with('clergy')
                ->select('id', 'name', 'first_name', 'last_name', 'email', 'phone', 'avatar', 'cover_photo', 'gender', 'role')
                ->find($user->id);

            return Helper::jsonResponse(true, 'Clergy profile updated successfully', 200, $fresh);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ClergyProfileController::update '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to update clergy profile', 500);
        }
    }

    private function clergyUser(): User
    {
        $user = auth('api')->user();

        if (! $user || $user->role !== 'clergy') {
            throw new AuthorizationException('Only clergy can access this resource');
        }

        return $user;
    }
}
