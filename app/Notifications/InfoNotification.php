<?php

namespace App\Notifications;

use App\Mail\InfoMailWithQrCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class InfoNotification extends Notification
{
    use Queueable;

    private $requestData;

    /**
     * Create a new notification instance.
     */
    public function __construct($requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['mail'];
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new InfoMailWithQrCode(
            $this->requestData['user'],
            $this->requestData['title'],
            $this->requestData['message'],
            $this->requestData['qrCodeImage'],
        ))->to($notifiable->email);
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
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
        return new BroadcastMessage([
            'title' => $this->requestData['title'],
            'message' => $this->requestData['message'],
            'url' => $this->requestData['url'],
            'type' => $this->requestData['type'],
            'thumbnail' => $this->requestData['thumbnail'],
        ]);
    }
}
