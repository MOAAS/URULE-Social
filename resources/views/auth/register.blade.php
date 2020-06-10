@extends('layouts.auth')

@section('title', 'Sign Up')

@section('content')
    <form class="m-3" method="POST" action="{{ route('register') }}">
        @csrf
        
        @include('partials.auth.form_input', ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'placeholder' => 'First and last name', 'value' => old('name'), 'required' => true, 'note' => 'How other users will see you.'])
        @include('partials.auth.form_input', ['label' => 'Email address', 'name' => 'email', 'type' => 'email', 'placeholder' => 'Enter email', 'value' => old('email'), 'required' => true, 'note' => "We'll never share your email with anyone else."])
        @include('partials.auth.form_input', ['label' => 'Password', 'name' => 'password', 'type' => 'password', 'placeholder' => 'Enter password', 'required' => true])
        @include('partials.auth.form_input', ['label' => 'Confirm Password', 'name' => 'password_confirmation', 'type' => 'password', 'placeholder' => 'Confirm Password', 'required' => true])

        <input type="submit" class="btn btn-primary btn-block mb-3" value="Sign Up">
        <a href="{{url('redirect')}}" class="btn-auth-secondary btn btn-primary btn-block mb-3">
            @include('partials.auth.google_icon', ['size' => 24])&nbsp; Sign Up with Google
        </a>
    </form>

    <div class="row text-center align-items-center">
        <div class="col-6 border-right"><a href="{{ route('login') }}" >Sign In</a></div>        
        <div class="col-6"><a href="{{ route('password.form') }}">Forgot Password?</a></div>
    </div>
@endsection
