@extends('layouts.auth')

@section('title', 'Password Reset')

@section('content')
    @if (session('status'))
        <div class="alert alert-success my-3" role="alert">{{ session('status') }}</div>
    @endif

    <form class="m-3" method="POST" action="{{ route('password.email') }}">
        @csrf
        
        @include('partials.auth.form_input', ['label' => 'Email address', 'name' => 'email', 'type' => 'email', 'placeholder' => 'Your account\'s email address', 'required' => true])

        <input type="submit" class="btn btn-primary btn-block mb-3" value="Send Password Reset Link">
    </form>

    <div class="row text-center align-items-center">
        <div class="col-6 border-right"><a href="{{ route('login') }}" >Sign In</a></div>        
        <div class="col-6"><a href="{{ route('register') }}" >Sign Up</a></div>
    </div>
@endsection