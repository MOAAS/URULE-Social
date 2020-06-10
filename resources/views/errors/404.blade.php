@extends('layouts.app', ['css_files' => ['auth.css']])

@section('title', 'Not found')

@section('body')
    @include ('partials.brand.large')

    <div class="container mt-5 display-4">
        Woops!
    </div>
    <div class="container my-5 display-4">
        The page you were looking for could not be found.
    </div>
    <div class="container mb-5 text-center">
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Back to Home</a>
    </div>


@endsection