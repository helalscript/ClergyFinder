<?php

use App\Models\ChatRoom;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatRoomId}', function ($user, $chatRoomId) {
    if (! $user) {
        return false;
    }

    if (($user->role ?? null) === 'admin') {
        return true;
    }

    $room = ChatRoom::find($chatRoomId);
    if (! $room) {
        return false;
    }

    return (int) $user->id === (int) $room->seeker_id || (int) $user->id === (int) $room->clergy_id;
});
