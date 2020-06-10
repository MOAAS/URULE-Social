<?php

namespace App\Policies;

use App\User;
use App\GroupMember;

use Illuminate\Support\Facades\Auth;

class GroupMemberPolicy
{    
    public function create(User $user, GroupMember $member)
    {
        return $member->group->owner_id == $user->user_id && $user->friends_with($member->user);
    }

    public function delete(User $user, GroupMember $member)
    {
        return $member->group->owner_id == $user->user_id;
    }
}