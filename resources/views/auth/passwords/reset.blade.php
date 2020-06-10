@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <form class="m-3" method="POST" action="{{ route('password.update') }}">
        @csrf
        
        <input type="hidden" name="token" value="{{ $token }}">
        
        @include('partials.auth.form_input', ['label' => 'Email address', 'name' => 'email', 'type' => 'email', 'placeholder' => 'Your account\'s email address', 'value' => $email ?? old('email'), 'required' => true])
        @include('partials.auth.form_input', ['label' => 'Password', 'name' => 'password', 'type' => 'password', 'placeholder' => 'Enter password', 'value' => old('password')])
        @include('partials.auth.form_input', ['label' => 'Confirm Password', 'name' => 'password_confirmation', 'type' => 'password', 'placeholder' => 'Confirm Password', 'value' => old('password_confirmation')])

        <input type="submit" class="btn btn-primary btn-block mb-3" value="Reset Password">

        <div class="row text-center align-items-center">
            <div class="col-6 border-right"><a href="{{ route('login') }}" >Sign In</a></div>        
            <div class="col-6"><a href="{{ route('register') }}" >Sign Up</a></div>
        </div>
    </form>
@endsection