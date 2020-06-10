<div id="add-to-group{{$group->group_id}}" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body form-group">
                <ul class="centered-content">
                @foreach ($group->notInGroup as $user)
                    <li class="add-to-group-btn list-group-item d-flex align-items-center" data-group-id="{{$group->group_id}}" data-user-id="{{$user->user_id}}">
                        @include('partials.user.avatar_no_link', ['user' => $user])
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
