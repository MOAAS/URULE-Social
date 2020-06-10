<li class="list-group-item d-flex align-items-center justify-content-between">
    <span class="number">{{$normal_user->user_id}}</span>
    @include('partials.user.avatar', ['user' => $normal_user])
    @if($normal_user->user_id == $user->user_id)
        <button class="btn btn-outline-secondary btn-sm ml-auto mr-2" title="Ban user" disabled> <i class="fas fa-gavel fa-fw"></i></button>
    @elseif(!$normal_user->ban)
        <button class="btn btn-danger btn-sm ml-auto mr-2"  title="Ban user"  data-toggle="modal" data-user-id="{{$normal_user->user_id}}" data-target="#ban-user{{$normal_user->user_id}}"> <i class="fas fa-gavel fa-fw"></i></button>
    @else
        <button class="btn btn-success btn-sm ml-auto mr-2"  title="Unban user" data-toggle="modal" data-target="#unban-user{{$normal_user->user_id}}" data-user-id="{{$normal_user->user_id}}"> <i class="fas fa-unlock fa-fw"></i></button>
    @endif
    @include('partials.admin.user_ban_modal', ['normal_user' => $normal_user])
    @include('partials.admin.user_unban_modal', ['normal_user' => $normal_user])
</li>
