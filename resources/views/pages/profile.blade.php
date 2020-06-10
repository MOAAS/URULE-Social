@extends('layouts.main', ['css' => 'profile.css', 'js' => 'profile.js', 'nav' => $self ? 'profile' : ''])

@section('title', "{$profile_user->name}'s Profile")

@section('content')
    <div id="profile-page" class="p-0 col-sm-10 page-section centered-content" data-user-id="{{$profile_user->user_id}}">
        @include('partials.navbars.topbar', [
            'user' => $user, 
            'nav' => $self ? 'profile' : '',
        ])
            
        @includeWhen($self, 'partials.user.banner', ['user' => $profile_user, 'buttons' => ['edit']])
        @includeWhen($friends_with, 'partials.user.banner', ['user' => $profile_user, 'buttons' => ['message', 'unfriend']])
        @includeWhen($requested, 'partials.user.banner', ['user' => $profile_user, 'buttons' => ['requested']])
        @includeWhen($received, 'partials.user.banner', ['user' => $profile_user, 'buttons' => ['accept']])
        @includeWhen($user == null, 'partials.user.banner', ['user' => $profile_user, 'buttons' => []])
        @includeWhen(!$self && !$friends_with && !$requested && !$received && $user != null, 'partials.user.banner', ['user' => $profile_user, 'buttons' => ['request']])

        <div id="profile-info" class="centered-content">
            <p id="profile-name" class="font-weight-bold my-1 text-smart-break">{{ $profile_user->name }}</p>
            @isset ($profile_user->birthday)
            <p id="profile-birthday"  class="my-1">🎂 {{ $profile_user->birthday_short }}</p>
            @endisset
            <p id="profile-location" class="my-1"><i class="fa fa-map-marker-alt mr-2"></i>{{ $profile_user->location ?? 'Unknown' }} </p>
            <a id="profile-friends" href="{{ route('friends', user_route_params($profile_user)) }}" class="my-1"><i class="fa fa-user-friends"></i> {{ $profile_user->friend_count() }}</a>
        </div>

        <div id="profile-posts" class="centered-content">
            @foreach ($posts as $post)
                @include('partials.content.post', ['post' => $post, 'user' => $user, 'onPostPage' => false])
            @endforeach
        </div>

        @if (count($posts) > 5)
            @include("partials.spinner")
        @endif
    </div>
@endsection