<div id="edit-content{{$content_id}}" class="modal fade" tabindex="-1" role="dialog">
    <form class="{{ $is_post ? 'edit-post-form' : 'edit-comment-form' }} modal-dialog modal-lg" data-content-id="{{$content_id}}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit {{ $is_post ? 'Post' : 'Comment' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body form-group">
                <label for="message-text{{$content_id}}" class="col-form-label">{{ $is_post ? 'Post' : 'Comment' }} Content:</label>
                <textarea class="form-control" name="content" id="message-text{{$content_id}}" rows="4">{{ $content }}</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </form>
</div>
