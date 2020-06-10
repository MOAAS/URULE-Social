@if (isset($group))
    <li class="list-group-item list-group-item-secondary d-flex align-items-center friend-group" data-group-id="{{$group->group_id}}">
        <h4 class="m-0 d-inline text-truncate">{{ $group->group_name }}</h4>
        @if ($manageable)
            @include('partials.friend.group_add_modal', ['group' => $group])
            @include('partials.friend.group_edit_modal', ['group' => $group])
            @include('partials.friend.group_delete_modal', ['group' => $group])
            <div class="dropleft p-2 ml-auto">
                <button class="btn text-dark" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>                
                <div class="dropdown-menu">
                    <button class="dropdown-item" data-toggle="modal" data-target="#add-to-group{{$group->group_id}}">Add member</button>
                    <button class="dropdown-item" data-toggle="modal" data-target="#edit-group{{$group->group_id}}">Rename</button>
                    <button class="dropdown-item" data-toggle="modal" data-target="#delete-group{{$group->group_id}}">Delete</button>
                </div>
            </div>
        @endif
    </li>
@else
    <li class="list-group-item list-group-item-secondary d-flex align-items-center friend-group">
        <h4 class="m-0 d-inline text-truncate">Friends</h4>
    </li>
@endif
