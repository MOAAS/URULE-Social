<?php

namespace App\Policies;

use App\User;
use App\Friend;

use Illuminate\Support\Facades\Auth;

class FriendPolicy
{
  public function delete(User $user) 
  {
    return Auth::check();
  }
}