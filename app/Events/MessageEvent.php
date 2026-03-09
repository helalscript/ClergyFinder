<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $message;
    public $chatRoomId;
    public $sender;

    public function __construct($message, $chatRoomId, $sender)
    {
        $this->message = $message;
        $this->chatRoomId = $chatRoomId;
        $this->sender = $sender;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('chat.' . $this->chatRoomId),
        ];
    }

}
