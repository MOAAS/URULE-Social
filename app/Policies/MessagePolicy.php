<?php

namespace App\Policies;

use App\User;
use App\Message;

use Illuminate\Support\Facades\Auth;

class MessagePolicy
{
    public function send(User $user, Message $message)
    {
        return $user->friends_with($message->receiver);
    }

    public function delete(User $user, Message $message)
    {
        return $user->user_id == $message->sender_id;
    }
}