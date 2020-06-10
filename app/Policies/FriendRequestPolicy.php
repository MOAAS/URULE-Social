<?php

namespace App\Policies;

use App\User;
use App\FriendRequest;

use Illuminate\Support\Facades\Auth;

class FriendRequestPolicy
{
  public function list(User $user)
  {
    return Auth::check();
  }

  public function create(User $user) //, FriendRequest $friend_request)
  {
    // Only the sending user can create a friend request
    // return $user->user_id == $friend_request->user_from;
    return Auth::check();
  }

  public function update(User $user) //, FriendRequest $friend_request)
  {
    // Only the receiving user can delete a friend request
   //  return $user->user_id == $friend_request->user_to;
    return Auth::check();
  }
}