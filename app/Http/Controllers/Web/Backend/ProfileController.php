<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Services\Web\Settings\ProfileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $profileService;
    protected $user;


    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
        $this->user = Auth::user();
    }


    public function index()
    {
        try {
            $user = $this->profileService->get();
            // dd($profile);
            return view("backend.layouts.settings.profile", compact("user"));
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function passwordChange()
    {
        return view("backend.layouts.settings.profile_password");
    }
    /**
     * Update the user's profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female,others',
            'avatar' => 'nullable|image|max:2048',
        ]);
        try {
            $updatedProfile = $this->profileService->update($this->user, $validatedData);
            if ($updatedProfile) {
                flash()->success('Profile Update Succesfull');
                return redirect()->route('profile_settings.index');
            } else {
                // Flash error message
                flash()->error('Something went wrong');
                return redirect()->back();
            }
        } catch (Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            // Flash error message
            flash()->error('Something went wrong');
            return redirect()->back();
        }
    }

    public function UpdatePassword(Request $request)
    {
        $validatedData = $request->validate([
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Check if the old password matches the current password
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('The old password is incorrect.');
                    }
                },
            ],
            'password' => 'required|confirmed|min:8',
        ]);
        $updatedProfile = $this->profileService->updatePassword($this->user, $validatedData);
        if ($updatedProfile) {
            flash()->success('Password updated successfully');
            return redirect()->route('profile_settings.password_change');
        }
    }
}
