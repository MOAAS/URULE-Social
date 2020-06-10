<div id="create-comment-card" class="card shadow-sm my-4">
    <div class="card-title bg-light p-0 m-0">
        @include('partials.user.avatar', ['user' => $user])
    </div>
    

    <form id="comment-form" data-post-id="{{$post_id}}" action="{{ route('comment.create', $post_id) }}"  method="post">
        @csrf

        <textarea name="content" id="comment-input" class="form-control" placeholder="Write your comment here..." rows="4"></textarea>
        <button id="comment-btn" type="submit" class="ml-auto btn btn-primary">Comment</button>
    </form>
</div>