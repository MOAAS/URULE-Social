<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaticPageController extends Controller
{
    public function home() {
        return redirect('login');
    }

    public function about() {
        return view('pages.about', ['user' => Auth::user()]);
    }

    public function e403() {
        return view('errors.403');
    }

    public function e404() {
        return view('errors.404');
    }
}
