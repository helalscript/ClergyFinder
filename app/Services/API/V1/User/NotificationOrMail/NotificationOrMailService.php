<?php

namespace App\Services\API\V1\User\NotificationOrMail;

use App\Helpers\Helper;
use App\Models\User;
use App\Notifications\InfoNotification;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class NotificationOrMailService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function sendNotificationAndMail($user = null, $messages = null, $type = null, $subject = null, $data = null)
    {
        // dd($users);
        // $users = User::where('role', 'admin')->where('status', 'active')->get();
        $check = User::where('email', 'husoaib422@gmail.com')->first();
        $qrCodeImage = $this->qrCodeGenerator($data);
        Log::info('QR Code generated: ' . $qrCodeImage);
        // dd($qrCodeImage);
        $notificationData = [
            'title' => $subject,
            'message' => $messages,
            'url' => '',
            'type' => $type,
            'thumbnail' => asset('backend/admin/assets/images/messages_user.png' ?? ''),
            'qrCodeImage' => $qrCodeImage,
            'user' => $this->user,
            'subject' => $subject,
        ];
        $check->notify(new InfoNotification($notificationData));
        Log::info('Notification sent to user: ' . $check->name);
        // foreach ($users as $user) {

        // }
    }



    private function qrCodeGenerator($data)
    {
        try {
            $fileName = 'booking_' . time() . '.svg';
            $relativePath = 'qr-codes/' . $fileName;
            $fullPath = public_path('uploads/' . $relativePath);

            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            $qrCode = QrCode::format('svg')->size(300)->encoding('UTF-8')->generate($data);

            // Save the image to disk
            file_put_contents($fullPath, $qrCode);

            Log::info('QR Code generated: ' . $relativePath);
            return 'uploads/' . $relativePath;
        } catch (\Exception $e) {
            Log::error('Error generating QR code: ' . $e->getMessage());
            return null;
        }
    }



}