<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{

    /**
     * Fetch all the notifications for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $notifications = auth()->user()->notifications;
            return Helper::jsonResponse(true, "Notifications fetched successfully", 200, $notifications);
        } catch (Exception $e) {
            Log::error('NotificationController::index Error: ' . $e->getMessage());
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Mark notifications as read
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        $validatedData = $request->validate([
            'notification_ids' => 'required|array',
        ]);
        try {
            $user = auth()->user();
            $user->notifications()->whereIn('id', $validatedData['notification_ids'])->update(['read_at' => now()]);
            return Helper::jsonResponse(true, 'Notifications marked as read', 200);
        } catch (Exception $e) {
            Log::error('NotificationController::markAsRead Error: ' . $e->getMessage());
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete a notification by its ID.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Exception
     */
    public function delete($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->delete();

            return Helper::jsonResponse(true, 'Notification deleted successfully.', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('NotificationController::delete Error: ' . $e->getMessage());
            return Helper::jsonErrorResponse('Notification not found.', 404);
        } catch (Exception $e) {
            Log::error('NotificationController::delete Error: ' . $e->getMessage());
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Delete all notifications for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function deleteAll()
    {
        try {
            auth()->user()->notifications()->delete();
            return Helper::jsonResponse(true, 'All notifications deleted successfully.', 200);
        } catch (Exception $e) {
            Log::error('NotificationController::deleteAll Error: ' . $e->getMessage());
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }
}
