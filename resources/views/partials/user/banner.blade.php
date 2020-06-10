<div id="user-banner" class="banner-photo-container border-bottom">
    @include('partials.user.banner_photo', ['user' => $user])
    @include('partials.user.avatar_large', ['user' => $user])

    <div id="profile-banner-buttons">
        @if(in_array('edit', $buttons))
            <button type="button" data-toggle="modal" data-target="#edit-profile-modal" title="Edit profile"><i class="fas fa-edit"></i></button>
            @include('partials.user.edit_modal')
        @endif

        @if(in_array('message', $buttons))
            <button onclick="location.href='{{ route('conversation', user_route_params($user)) }}'" title="Message user"><i class="fas fa-envelope"></i></button>
        @endif

        @if(in_array('unfriend', $buttons))
            <button id="unfriend-btn" type="button" title="Unfriend user" data-user-id="{{$user->user_id}}"><i class="fas fa-user-minus"></i></button>
        @endif

        @if(in_array('request', $buttons))
            <button id="request-btn" type="button" title="Send friend request" data-user-id="{{$user->user_id}}"><i class="fas fa-user-plus"></i></button>
        @endif

        @if(in_array('accept', $buttons))
            <button id="accept-btn" type="button" title="Accept friend request" data-user-id="{{$user->user_id}}" class="bg-success"><i class="fas fa-user-plus"></i></button>
        @endif

        @if(in_array('requested', $buttons))
            <button type="button" disabled="disabled" title="Request already sent"><i class="fas fa-user-plus"></i></button>
        @endif
    </div>
</div>