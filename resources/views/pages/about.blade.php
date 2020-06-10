@extends('layouts.main', ['nav' => 'about'])

@section('title', 'About')

@section('content')
    <section id="about-page" class="p-0 col-sm-10 page-section">
        @include('partials.navbars.topbar', ['user' => $user, 'nav' => 'about'])

        <div class="jumbotron bg-white centered-content">
            <h2 class="display-4 website-brand-primary">
                @include('partials.brand.logo_dupe')
                <span class="website-name">URULE</span>
            </h2>
            <p class="lead">This is a social network designed to allow users to create new relationships, making it easier to share moments with friends, and connecting people in a fun way.
            <hr class="my-4">
            <p>You can share your thoughts and images with every other user, or privately message a long time friend. </p>
            <p>Brought to you by:</p>
            <ul>
                <li>Alexandre Carqueja</li>
                <li>Daniel Brandão</li>
                <li>Henrique Santos</li>
                <li>Pedro Moás</li>
            </ul>
        </div>
    </section>
@endsection
