<div class="modal fade" id="unban-user{{$normal_user->user_id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="{{ route('admin.unban', $normal_user->user_id) }}" class="unban-user-form modal-dialog modal-lg" data-user-id="{{$normal_user->user_id}}">
        @csrf
        <input type="hidden" name="_method" value="PUT"/>

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unban {{$normal_user->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Reason of ban</h5>
                <p id="unban-user-reason{{$normal_user->user_id}}">
                @if($normal_user->ban)
                    {{$normal_user->ban->reason_of_ban}}
                @endif
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Unban</button>
            </div>
        </div>
    </form>
</div>