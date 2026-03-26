<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Clergy;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Fetch the authenticated user's details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
       try {
        $user = User::with('clergy:id,user_id,religion,bio,address,city,preferred_language,available_at,specialities,is_verified,is_live_session,is_prayer_request,status')
        ->select('id', 'name', 'email', 'phone', 'avatar', 'cover_photo', 'gender', 'role')
        ->find(auth('api')->id());
        if (!$user) {
            return Helper::jsonResponse(false, 'User not found', 404);
        }
        return Helper::jsonResponse(true, 'User details fetched successfully', 200, $user);
       } catch (Exception $e) {
        Log::error('UserController::me ' . $e->getMessage());
        return Helper::jsonErrorResponse('something went wrong', 403);
       }
    }

    /**
     * Update the authenticated user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:100',
            'gender' => 'nullable|string|in:male,female,others',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'phone' => 'nullable|unique:users,phone,' . auth()->user()->id . '|numeric|max_digits:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        try {
            if (!empty($validatedData['password'])) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            } else if (array_key_exists('password', $validatedData)) {
                unset($validatedData['password']);
            }

            $user = auth('api')->user();
            //upload avatar photo
            if ($request->hasFile('avatar')) {
                if (!empty($user->avatar)) {
                    Helper::fileDelete(public_path($user->getRawOriginal('avatar')));
                }
                $validatedData['avatar'] = Helper::fileUpload($request->file('avatar'), 'user/avatar', getFileName($request->file('avatar')));
            } else {
                $validatedData['avatar'] = $user->avatar;
            }
            //upload cover photo
            if ($request->hasFile('cover_photo')) {
                if (!empty($user->cover_photo)) {
                    Helper::fileDelete(public_path($user->getRawOriginal('cover_photo')));
                }
                $validatedData['cover_photo'] = Helper::fileUpload($request->file('cover_photo'), 'user/cover_photo', getFileName($request->file('cover_photo')));
            } else {
                $validatedData['cover_photo'] = $user->cover_photo;
            }
            // set name
            $name = null;
            if (!empty($validatedData['first_name']) && !empty($validatedData['last_name'])) {
                $name = $validatedData['first_name'] . ' ' . $validatedData['last_name'];
            }
            $validatedData['name'] = $name;

            $user->update($validatedData);
            return Helper::jsonResponse(true, 'Profile updated successfully', 200, $user);
        } catch (Exception $e) {
            Log::error('UserController::updateProfile' . $e->getMessage());
            return Helper::jsonErrorResponse('something went wrong', 403);
        }
    }

    /**
     * Update the authenticated user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        try {
            $user = auth('api')->user();

            // Check if the current password matches the stored password
            if (!\Hash::check($validatedData['current_password'], $user->password)) {
                return Helper::jsonErrorResponse('Current password is incorrect', 422);
            }

            // Update the password
            $user->update([
                'password' => bcrypt($validatedData['new_password']),
            ]);
            return Helper::jsonResponse(true, 'Password updated successfully', 200);
        } catch (Exception $e) {
            Log::error('UserController::changePassword' . $e->getMessage());
            return Helper::jsonErrorResponse('something went wrong', 403);
        }
    }

    public function deleteProfile()
    {
        try {
            // Get the authenticated user
            $user = User::findOrFail(auth('api')->id());

            // If the user has an avatar, attempt to delete it
            if (!empty($user->avatar)) {
                // Ensure that the file exists before attempting to delete
                $avatarPath = public_path($user->avatar);
                if (file_exists($avatarPath)) {
                    Helper::fileDelete($avatarPath);
                }
            }

            // Soft delete the user (if using SoftDeletes)
            $user->delete();

            // Return success response
            return Helper::jsonResponse(true, 'Profile deleted successfully', 200);
        } catch (Exception $e) {
            Log::error('UserController::deleteProfile' . $e->getMessage());
            // Return error response
            return Helper::jsonErrorResponse('Something went wrong. Please try again later.', 403);
        }
    }

    /**
     * Update authenticated clergy profile (user + clergy table).
     *
     * Supports `preferred_language` and `specialities` as array or comma-separated string.
     */
    public function ClergyProfileUpdate(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            return Helper::jsonResponse(false, 'Unauthenticated', 401);
        }
        if (($user->role ?? null) !== 'clergy') {
            return Helper::jsonResponse(false, 'Only clergy can update this profile', 403);
        }

        $validated = $request->validate([
            // user fields
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|unique:users,phone,' . $user->id . '|numeric|max_digits:20',
            'gender' => 'nullable|string|in:male,female,others',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',

            // clergy fields
            'religion' => 'nullable|string|max:255',
            'preferred_language' => 'nullable',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bio' => 'nullable|string',
            'available_at' => 'nullable|string|max:255',
            'specialities' => 'nullable',
            'is_verified' => 'nullable|boolean',
            'is_live_session' => 'nullable|boolean',
            'is_prayer_request' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Normalize array-ish fields (string "a,b" OR ["a","b"])
            $preferredLanguage = $request->input('preferred_language');
            if (is_string($preferredLanguage)) {
                $preferredLanguage = array_values(array_filter(array_map('trim', explode(',', $preferredLanguage))));
            } elseif (!is_array($preferredLanguage)) {
                $preferredLanguage = null;
            }

            $specialities = $request->input('specialities');
            if (is_string($specialities)) {
                $specialities = array_values(array_filter(array_map('trim', explode(',', $specialities))));
            } elseif (!is_array($specialities)) {
                $specialities = null;
            }

            // Upload avatar
            if ($request->hasFile('avatar')) {
                if (!empty($user->avatar)) {
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

            // Upload cover photo
            if ($request->hasFile('cover_photo')) {
                if (!empty($user->cover_photo)) {
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

            // set full name
            $validated['name'] = trim(($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''));

            // Update user
            $user->update([
                'name' => $validated['name'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'] ?? $user->phone,
                'gender' => $validated['gender'] ?? $user->gender,
                ...array_intersect_key($validated, array_flip(['avatar', 'cover_photo'])),
            ]);

            // Update clergy
            $clergyPayload = [
                'religion' => $validated['religion'] ?? null,
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
                'is_verified' => (bool) $request->input('is_verified', false),
                'is_live_session' => (bool) $request->input('is_live_session', true),
                'is_prayer_request' => (bool) $request->input('is_prayer_request', true),
            ];

            Clergy::updateOrCreate(['user_id' => $user->id], $clergyPayload);

            DB::commit();

            $fresh = User::with('clergy')
                ->select('id', 'name', 'first_name', 'last_name', 'email', 'phone', 'avatar', 'cover_photo', 'gender', 'role')
                ->find($user->id);

            return Helper::jsonResponse(true, 'Clergy profile updated successfully', 200, $fresh);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('UserController::ClergyProfileUpdate ' . $e->getMessage());
            return Helper::jsonErrorResponse('something went wrong', 403);
        }
    }

}
