@extends('layouts.main', ['css' => 'feed.css', 'js' => 'feed.js', 'nav' => $hot ? 'hot' : 'feed'])

@section('title', $hot ? 'Hot Page' : 'News feed')

@section('content')
    <section id="main-page" class="p-0 col-sm-10 page-section">
        @include('partials.navbars.topbar', ['user' => $user, 'nav' => 'feed'])

        <div id="post-list" class="centered-content">
            @each('partials.feed.announcement', \App\Announcement::active_announcements()->get(), 'announcement')
            @auth
                @include('partials.feed.post_form')
            @endauth

            @forelse ($posts as $post)
                @include('partials.content.post', ['post' => $post, 'user' => $user, 'onPostPage' => false])
            @empty
                @if ($hot)
                    <div class="container text-center mt-5 h3">No one seems to have posted anything recently...</div>
                @else
                    <div class="container text-center mt-5 h3">We couldn't find any relevant posts for you. Go make some friends!</div>
                @endif
            @endforelse
        </div>
        @if (count($posts) > 5)
            @include("partials.spinner")
        @endif
    </section>
@endsection

