<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthGoogleController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $dbUser = User::where('email', $googleUser->email)->first();
            if ($dbUser) {
                if (!$dbUser->isGoogleAccount())
                    return redirect()->route('login')->withMessage("An non-google account with this email already exists");
                Auth::login($dbUser);
            } else {
                $user = new User;
                $user->name = $googleUser->name;
                $user->email = $googleUser->email;
                $user->google_id = $googleUser->id;
                $user->password = md5(rand(1, 10000));
                $user->save();
                Auth::login($user);
            }
            return redirect()->route('hot');
        }
        catch(InvalidStateException $e){
            return redirect()->route('login');
      //      abort(500, $e->getTraceAsString());
        }
    }
}