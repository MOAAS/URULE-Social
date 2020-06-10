@extends('layouts.main', ['css' => 'friends.css', 'js' => 'friends.js', 'nav' => null])

@section('title', $profile_user->name . '\'s friends')

@section('content')
    <div id="friend-list-page" class="page-section col-12 col-sm-10 p-0">
        @include('partials.navbars.topbar', [
            'user' => $user, 
            'nav' => null,
        ])
        <ul class="centered-content">
            @php
                $self = $user != null && $user->user_id == $profile_user->user_id;
            @endphp
            @if ($self)
                <li class="list-group-item p-0 mb-3 border-0 rounded-0">
                    <form id="add-group-form" method="post" action="{{ route('groups.create') }}">
                        @csrf
                        <input id="add-group-input" type="text" placeholder="Create a group" class="form-control rounded-0 p-4" name="name">
                        <button id="add-group-btn" type="submit" class="btn btn-primary">Add</button>
                    </form>
                </li>
            @endif

            @foreach ($groups as $group)
                @include('partials.friend.group', ['group' => $group, 'manageable' => true])
                @forelse ($group->members as $member)
                    @include('partials.friend.item', ['user' => $member, 'deletable' => true, 'unfriendable' => false, 'group' => $group])
                @empty
                    <li class="list-group-item friend-group-empty"><p class="m-0 h-3">This list is empty.</p></li>
                @endforelse
            @endforeach

            
            @include('partials.friend.group', ['group' => null])

            @forelse ($grouplessFriends as $group_user)
                @include('partials.friend.item', ['user' => $group_user, 'deletable' => false, 'unfriendable' => $self])
            @empty
                <li class="list-group-item friend-group-empty"><p class="m-0 h-3">This list is empty.</p></li>
            @endforelse

        </ul>

    </div>
@endsection