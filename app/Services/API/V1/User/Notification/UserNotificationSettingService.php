<?php

namespace App\Services\API\V1\User\Notification;

use App\Models\UserCustomNotification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserNotificationSettingService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    /**
     * Update a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function updateUserNotification(int $id)
    {
        try {
            $notifications = UserCustomNotification::where('user_id', $this->user->id)
                ->where('id', $id)->firstOrFail();
            $notifications->status = $notifications->status == 'active' ? 'inactive' : 'active';
            $notifications->save();
            return $notifications;
        } catch (Exception $e) {
            Log::error("UserNotificationService::update" . $e->getMessage());
            throw $e;
        }
    }



    public function userNotificationSettings($request)
    {
        try {
            $per_page = $request->has('per_page') ? $request->per_page : 25;
            $notifications = UserCustomNotification::where('user_id', $this->user->id)
                ->with('customNotification')
                ->whereHas('customNotification', function ($query) {
                    $query->where('status', 'active');
                })
                ->paginate($per_page);
            return $notifications;
        } catch (Exception $e) {
            Log::error("UserNotificationService::userNotificationSettings" . $e->getMessage());
            throw $e;
        }
    }

}