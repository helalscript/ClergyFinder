<?php

namespace App\Services\Web\Settings;

use App\Helpers\Helper;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileService
{
    /**
     * Fetch all resources.
     *
     * @return mixed
     */
    public function get()
    {
        try {
            $user = Auth::user();
            return $user;
        } catch (Exception $e) {
             throw $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        try {
            // Logic for create form
        } catch (Exception $e) {
             throw $e;
        }
    }

    /**
     * Store a new resource.
     *
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        try {
            // Logic to store a new resource
        } catch (Exception $e) {
             throw $e;
        }
    }

    /**
     * Display a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        try {
            // Logic to show a specific resource
        } catch (Exception $e) {
             throw $e;
        }
    }

    /**
     * Show the form for editing a resource.
     *
     * @param int $id
     * @return void
     */
    public function edit(int $id)
    {
        try {
            // Logic for edit form
        } catch (Exception $e) {
             throw $e;
        }
    }

    /**
     * Update a specific resource.
     *
     * @param int $user
     * @param array $validatedData
     * @return mixed
     */
    public function update(User $user, array $validatedData)
    {
        try {
            DB::beginTransaction();
            // Handle avatar upload if provided
            if (isset($validatedData['avatar'])) {
                if (($validatedData['avatar'])) {
                    // Delete old avatar if it exists
                    if ($user->avatar && file_exists(public_path($user->avatar))) {
                        Helper::fileDelete(public_path($user->avatar));
                    }

                    $validatedData['avatar'] = Helper::fileUpload(
                        $validatedData['avatar'],
                        'avatar',
                        $user->name . time() . '.' . $validatedData['avatar']->getClientOriginalExtension()
                    );
                }
            }
            if ($user->role == "admin") {
                $user->update($validatedData);
            } else {
                unset($validatedData['email']);
                $user->update($validatedData);
            }

            DB::commit();

            return true;
        } catch (Exception $e) {
            Log::error("Profile Update Error: " . $e->getMessage());
            DB::rollBack();
             throw $e;
        }

    }
    /**
     * Update a specific resource.
     *
     * @param int $user
     * @param array $validatedData
     */
    public function updatePassword(User $user, array $validatedData)
    {
        try {
            DB::beginTransaction();
            // Update the password
            $user->password = Hash::make($validatedData['password']);
            $user->save();
            DB::commit();
            return true;
        } catch (Exception $e) {
            Log::error("Profile Password Update Error: " . $e->getMessage());
            DB::rollBack();
             throw $e;
        }

    }

    /**
     * Delete a specific resource.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        try {
            // Logic to delete a specific resource
        } catch (Exception $e) {
             throw $e;
        }
    }

    /**
     * Handle exceptions.
     *
     * @param Exception $e
     * @return mixed
     */
    private function handleException(Exception $e)
    {
        // Log the exception or handle it as needed
        // You can use logger or return an error response
        return [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }
}