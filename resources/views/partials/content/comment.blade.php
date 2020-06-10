<article class="card shadow-sm my-3 mx-md-3 comment" data-content-id="{{$comment->content_id}}">
    @auth
        @include('partials.content.header', [
            'content' => $comment,
            'can_delete' => $comment->author->user_id == $user->user_id || $user->admin,
            'can_edit' => $comment->author->user_id == $user->user_id,
            'can_report' => true,
            'is_post' => false
        ])
    @else
        @include('partials.content.header', ['content' => $comment, 'can_delete' => false, 'can_edit' => false, 'can_report' => false, 'is_post' => false])
    @endauth

    <div class="card-body">
        <p class="content-text card-text text-prewrap m-0">{{ $comment->content }}</p>
    </div>


    <div class="d-flex post-stats border-top">
        <span class="react-btn react-btn-upvote @auth clickable @endauth {{$comment->is_liked ? "react-btn-highlight" : ""}}" data-content-id="{{$comment->content_id}}"><i class="fas fa-thumbs-up"></i> <span class="p-0">{{$comment->likes}}</span></span>
        <span class="react-btn react-btn-downvote @auth clickable @endauth {{$comment->is_disliked ? "react-btn-highlight" : ""}}" data-content-id="{{$comment->content_id}}"><i class="fas fa-thumbs-down"></i> <span class="p-0">{{$comment->dislikes}}</span></span>
    </div>


</article>