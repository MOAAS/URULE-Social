@extends('layouts.app', ['css' => 'auth.css'])

@section('body')
    @include ('partials.brand.large')
    
    <section id="auth-page" class="p-3">
        <h2>@yield('title')</h2>
        @yield('content')
    </section>
@endsection
