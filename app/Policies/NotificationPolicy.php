<?php

namespace App\Policies;

use App\User;
use App\Notification;

use Illuminate\Support\Facades\Auth;

class NotificationPolicy
{
    public function delete(User $user, Notification $notification)
    {
        return $user->user_id == $notification->user_id;
    }

    public function create(User $user)
    {
        return Auth::check();
    }
}