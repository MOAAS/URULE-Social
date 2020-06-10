<?php

namespace App\Policies;

use App\User;

use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    public function update()
    {
        return Auth::check();
    }

    public function delete()
    {
        return Auth::check();
    }
}