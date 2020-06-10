<article class="card shadow-sm mx-md-3 my-3 search-result post" data-content-id="{{$content->content_id}}">
    <header class="p-2 card-title bg-light d-flex align-items-center m-0">
        @include('partials.user.avatar', ['user' => $content->author])         
        <small class="ml-auto mr-2 text-muted">{{ $content->content_date_short }}</small>
    </header>
    <a href="{{$content->url}}" class="card-body">
        <p class="card-text text-prewrap text-dark m-0">{{ $content->content }}</p>
    </a>

    <div class="d-flex post-stats border-top">
        <span class="react-btn react-btn-upvote @auth clickable @endauth {{$content->is_liked ? "react-btn-highlight" : ""}}" data-content-id="{{$content->content_id}}">
            <i class="fas fa-thumbs-up"></i>
            <span class="p-0">{{$content->likes}}</span>
        </span>
        <span class="react-btn react-btn-downvote @auth clickable @endauth {{$content->is_disliked ? "react-btn-highlight" : ""}}" data-content-id="{{$content->content_id}}">
            <i class="fas fa-thumbs-down"></i>
            <span class="p-0">{{$content->dislikes}}</span>
        </span>
        <a href="{{$content->url}}" class="ml-auto">
            <i class="fas fa-comment"></i>
            <span class="p-0">{{$content->post->comments}}</span>
        </a>
    </div>
</article>