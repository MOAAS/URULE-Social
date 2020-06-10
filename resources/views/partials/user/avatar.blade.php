@if (isset($user) && $user != null)
    @if ($user->user_id != -1)
        <a href="{{ $user->url }}" class="p-2 text-truncate profile-avatar">
            <img class="profile-picture-small" src="{{ $user->avatar }}" alt="{{$user->name}}'s avatar">
            <h3 class="d-inline h6">{{ $user->name }}</h3>
        </a>
    @else
        <div class="p-2 text-truncate profile-avatar">
            <img class="profile-picture-small" src="{{ $user->avatar }}" alt="{{$user->name}}'s avatar">
            <h3 class="d-inline h6">{{ $user->name }}</h3>
        </div>
    @endif
@else
    <div class="p-2 text-truncate profile-avatar avatar-guest">
        <img class="profile-picture-small" src="{{ asset('storage/guest_avatar.png')}}" alt="Guest Avatar">
        <h3 class="d-inline h6">Guest</h3>
    </div>
@endif
