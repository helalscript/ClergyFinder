<?php

namespace App\Services\API\V1\User\ContactSupport;

use App\Helpers\Helper;
use App\Models\ContactUs;
use App\Models\User;
use App\Notifications\ContactMessageNotification;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserContactSupportService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }



    /**
     * Store a new resource.
     *
     * @param array $validatedData
     * @return mixed
     */
    public function store(array $validatedData)
    {
        try {
            DB::beginTransaction();

            // Check for duplicate message submission within 24 hours
            $this->checkDuplicateMessage($validatedData['email']);
            $validatedData['ip_address'] = request()->ip();

            if (!empty($validatedData['file'])) {
                $validatedData['file'] = Helper::fileUpload($validatedData['file'], 'file', $validatedData['file'] . '_' . getFileName($validatedData['file']));
            }
 
            $contactSupport = ContactUs::create($validatedData);
            // Send notification to admins
            $this->sendAdminNotification($contactSupport);
            DB::commit();
            return $contactSupport;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("UserContactSupportService::store" . $e->getMessage());
            throw $e;
        }
    }

    private function checkDuplicateMessage($email)
    {
        $lastMessage = ContactUs::where('email', $email)
            ->latest()
            ->first();

        if ($lastMessage) {
            $lastSentTime = $lastMessage->created_at;
            $currentTime = now();

            if ($lastSentTime->diffInHours($currentTime) < 24) {
                throw new Exception('You have already sent a message in the last 24 hours. Please wait before sending another one.');
            }
        }
    }

    private function sendAdminNotification(ContactUs $contactMessage)
    {
        $adminUsers = User::where('role', 'admin')->where('status', 'active')->get();

        $notificationData = [
            'title' => 'A new Support Message has been submitted.',
            'message' => $contactMessage->message,
            'url' => route('admin_contact_us.index'),
            'type' => 'Contact Message',
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png' ?? ''),
        ];

        foreach ($adminUsers as $admin) {
            $admin->notify(new ContactMessageNotification($notificationData));
            Log::info('Notification sent to admin: ' . $admin->name);
        }
    }
}