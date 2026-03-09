<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ContactMessageNotification extends Notification
{
    use Queueable;

    private $requestData;

    /**
     * Create a new notification instance.
     */
    public function __construct($requestData)
    {
        Log::info('Broadcasting notification __construct');
        $this->requestData = $requestData;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
        // return ['database', 'broadcast'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        Log::info('Broadcasting notification toDatabase');

        return [
            'message' => $this->requestData['message'],
            'url' => $this->requestData['url'],
            'type' => $this->requestData['type'],
            'thumbnail' => $this->requestData['thumbnail'],
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        try {
            Log::info('Broadcasting notification toBroadcast');

            return new BroadcastMessage([
                'title' => $this->requestData['title'],
                'message' => $this->requestData['message'],
                'url' => $this->requestData['url'],
                'type' => $this->requestData['type'],
                'thumbnail' => $this->requestData['thumbnail'],
            ]);
        } catch (\Exception $e) {
            Log::error('ContactMessageNotification::toBroadcast'.$e->getMessage());

            return true;
        }
    }
}
