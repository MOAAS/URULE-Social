<?php

namespace App\Policies;

use App\User;

use Illuminate\Support\Facades\Auth;

class AppraisalPolicy
{
    public function create()
    {
        return Auth::check();
    }

    public function delete()
    {
        return Auth::check();
    }
}