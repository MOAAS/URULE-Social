<?php

namespace App\Policies;

use App\User;
use App\GroupOfFriends;

use Illuminate\Support\Facades\Auth;

class GroupPolicy
{    
    public function create(User $user)
    {
        return Auth::check();
    }

    public function update(User $user, GroupOfFriends $group)
    {
      return $user->user_id == $group->owner_id;
    }

    public function delete(User $user, GroupOfFriends $group)
    {
        return $user->user_id == $group->owner_id;
    }
}