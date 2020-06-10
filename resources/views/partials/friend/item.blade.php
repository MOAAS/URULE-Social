<li class="list-group-item d-flex align-items-center friend-item" data-user-id="{{$user->user_id}}">
    @include('partials.user.avatar', ['user' => $user])
    @if ($deletable)
        <button class="btn btn-danger ml-auto ungroup-btn" data-user-id="{{$user->user_id}}" data-group-id="{{$group->group_id}}"><i class="fa fa-trash"></i></button>
    @elseif ($unfriendable)
        <button class="btn btn-primary ml-auto unfriend-btn" data-user-id="{{$user->user_id}}">Unfriend</button>
    @endif
</li>
