@extends('layouts.main', ['css' => 'messages.css', 'js' => 'messages.js', 'nav' => 'messages'])

@section('title', 'Messages')

@section('content')
    <section id="conversations" class="page-section col-12 col-sm-10 col-lg-4 p-0 m-0 vh-100 d-flex flex-column overflow-auto">
        @include('partials.navbars.topbar', ['user' => $user, 'nav' => 'messages', 'page_title' => 'Messages'])

        <ul id="preview-list" class="p-0 flex-grow-1">
            @forelse ($previews as $preview)
                @include('partials.messages.conversationpreview', ['user' => $user, 'message' => $preview])
            @empty
                <div class="container text-center mt-5 h3">No messages to display.</div>
            @endforelse
        </ul>
    </section>

    <div id="message-history" class="page-section col-12 col-sm-10 col-lg-6 p-0 m-0 border border-top-0 border-black vh-100 d-flex flex-column">
        @include('partials.messages.messagehistory', ['selectedUser' => $selectedUser])
    </div>
@endsection