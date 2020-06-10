@extends('layouts.auth')

@section('title', 'Log In')

@section('content')
    @if (session('message'))
        <div class="alert alert-danger">{{session('message')}}</div>
    @endif
    <form class="m-3" method="POST" action="{{ route('login') }}">
        @csrf

        @include('partials.auth.form_input', ['label' => 'Email address', 'name' => 'email', 'type' => 'email', 'placeholder' => 'Enter email', 'value' => old('email'), 'required' => true])
        @include('partials.auth.form_input', ['label' => 'Password', 'name' => 'password', 'type' => 'password', 'placeholder' => 'Password', 'required' => true])

        <div class="form-group form-check">          
            <input id="remember-me" name="remember-me" type="checkbox" class="form-check-input" {{ old('remember-me') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember-me">Remember Me</label>
        </div>            
        
        <input type="submit" class="btn btn-primary btn-block mb-3" value="Log In">
        <a href="{{url('redirect')}}" class="btn-auth-secondary btn btn-primary btn-block mb-3">
            @include('partials.auth.google_icon', ['size' => 24])&nbsp; Log In with Google
        </a>
        <a href="{{ route('hot') }}" class="btn-auth-secondary btn btn-primary btn-block mb-3"><i class="fas fa-user-secret"></i>&nbsp; Enter as a Guest</a>
    </form>
    
    
    <div class="row text-center align-items-center">
        <div class="col-6 border-right"><a href="{{ route('register') }}" >Sign Up</a></div>        
        <div class="col-6"><a href="{{ route('password.form') }}">Forgot Password?</a></div>
    </div>

    <a class="d-block text-center my-3" href="{{ route('about') }}">About Page</a>
@endsection


