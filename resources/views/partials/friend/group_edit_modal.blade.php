<div id="edit-group{{$group->group_id}}" class="modal fade" tabindex="-1" role="dialog">
    <form action="{{ route('groups.edit', $group->group_id) }}" class="modal-dialog modal-lg edit-group-form" data-group-id="{{$group->group_id}}">
        @csrf
        <input type="hidden" name="_method" value="PUT"/>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body form-group">
                <label class="col-form-label" for="group-name{{$group->group_id}}" >Group Name:</label>
                <input class="form-control" id="group-name{{$group->group_id}}" name="name" type="text" value="{{ $group->group_name }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </form>
</div>
