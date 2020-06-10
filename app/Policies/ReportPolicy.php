<?php

namespace App\Policies;

use App\User;
use App\ContentReport;

use Illuminate\Support\Facades\Auth;

class ReportPolicy
{
    public function create(User $user)
    {
        return Auth::check();
    }
}