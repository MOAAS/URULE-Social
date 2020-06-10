<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() && Auth::user()->ban){
            $reason = Auth::user()->ban->reason_of_ban;
            Auth::logout();
            return redirect()->route('login')->withMessage("Your account is banned. Reason: " . $reason);
        }
        return $next($request);
    }
}
