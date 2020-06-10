<div class="modal fade" id="ban-user{{$normal_user->user_id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="{{ route('admin.ban', $normal_user->user_id) }}" class="ban-user-form modal-dialog modal-lg" data-user-id="{{$normal_user->user_id}}">
        @csrf
        <input type="hidden" name="_method" value="PUT"/>

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ban {{$normal_user->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="reason{{$normal_user->user_id}}" class="col-form-label">Reason:</label>
                    <textarea class="form-control" id="reason{{$normal_user->user_id}}" name="reason" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Ban</button>
            </div>
        </div>
    </form>
</div>