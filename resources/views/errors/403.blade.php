@extends('layouts.app', ['css_files' => ['auth.css']])

@section('title', 'Forbidden')

@section('body')
    @include ('partials.brand.large')

    <div class="container mt-5 display-4">
        Hey there!
    </div>
    <div class="container my-5 display-4">
        Seems like you do not have access to this page, so let's be more responsible next time.
    </div>
    <div class="container mb-5 text-center">
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Back to Home</a>
    </div>


@endsection