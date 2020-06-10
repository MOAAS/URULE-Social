@extends('layouts.main', ['css' => 'post.css', 'js' => 'post.js', 'nav' => ''])

@section('title', "{$post->author->name}'s Post, at {$post->content_date_short}")

@section('content')
    <section class="page-section col-12 col-sm-10 p-0">
        @include('partials.navbars.topbar', [
            'user' => $user, 
            'nav' => '',
        ])

        <div id="post-comments" class="centered-content">
            @include('partials.content.post', ['post' => $post, 'user' => $user, 'onPostPage' => true])

            @auth
                @include('partials.content.comment_form', ['user' => $user, 'post_id' => $post->content_id])
            @endauth

            @forelse ($comments as $comment)
                @include('partials.content.comment', ['comment' => $comment, 'user' => $user]) 
            @empty
                <div id="no-comments-notice" class="container text-center mt-5 h3">No one seems to have cared enough to comment. Be the first!</div>                 
            @endforelse

        </div>
        @if (count($comments) > 5)
            @include("partials.spinner")
        @endif
    </section>
@endsection