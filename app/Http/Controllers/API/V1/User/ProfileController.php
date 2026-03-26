<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\ProfileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    public function __construct(protected ProfileService $profileService)
    {
        // Middleware can be added here if needed
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'job_title' => 'nullable|string',
            // other fields like education, experience etc.
        ]);

        try {
            $profile = $this->profileService->store($validatedData);
            return Helper::jsonResponse(true, 'Profile updated successfully', 200, $profile);
        } catch (Exception $e) {
            Log::error("ProfileController::store" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to update profile ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
