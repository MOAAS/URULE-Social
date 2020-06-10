@extends('layouts.main', ['css' => 'requests.css', 'js' => 'requests.js', 'nav' => 'friend_requests'])

@section('title', 'Friend Requests')

@section('content')
    <section id="friend-requests-page" class="page-section col-12 col-sm-10 p-0">
        @include('partials.navbars.topbar', ['user' => $user, 'nav' => 'friend_requests'])

        <ul class="centered-content">
            @forelse ($friend_requests as $friend_request)
                <li class="list-group-item d-flex align-items-center transformable-slow">
                    @include('partials.user.avatar', ['user' => $friend_request->from])
                    <button class="friend-deny btn btn-danger btn-sm ml-auto mr-2" data-user-id="{{$friend_request->user_from}}" title="Deny"><i class="fa fa-times fa-fw"></i></button>
                    <button class="friend-accept btn btn-success btn-sm" data-user-id="{{$friend_request->user_from}}" title="Accept"><i class="fa fa-check fa-fw"></i></button>
                </li>
            @empty
                <div class="container text-center mt-5 h3">
                    No friend requests available.
                </div>
            @endforelse
        </ul>
    </section>
@endsection