<div id="delete-content{{$content_id}}" class="modal fade" tabindex="-1" role="dialog">
    <form class="{{ $is_post ? 'delete-post-form' : 'delete-comment-form' }} modal-dialog modal-lg" data-content-id="{{$content_id}}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete {{ $is_post ? 'Post' : 'Comment' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body form-group">
                <p>Are you sure you want to delete this {{ $is_post ? 'Post' : 'Comment' }}? This action is irreversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </form>
</div>
