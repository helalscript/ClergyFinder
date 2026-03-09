<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        try {
            $user = auth()->user();
            $user->notifications()->update(['read_at' => now()]);
            return response()->json([
                'success' => true,
                'message' => 'Notifications marked as read.',
            ], 200);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }
    public function markAsSingleRead($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id)->update(['read_at' => now()]);
            return response()->json([
                'success' => true,
                'message' => 'Notifications marked as read.',
            ], 200);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->delete();
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully.',
            ], 200);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    public function deleteAll()
    {
        try {
            auth()->user()->notifications()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Notification all clear successfully.',
            ], 200);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }
}
