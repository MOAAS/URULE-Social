<div id="delete-group{{$group->group_id}}" class="modal fade" tabindex="-1" role="dialog">
    <form action="{{ route('groups.delete', $group->group_id) }}" class="modal-dialog modal-lg delete-group-form" data-group-id="{{$group->group_id}}">
        @csrf
        <input type="hidden" name="_method" value="DELETE"/>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body form-group">
                <p>Are you sure you want to delete the group: {{$group->group_name}}? This action is irreversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </form>
</div>
