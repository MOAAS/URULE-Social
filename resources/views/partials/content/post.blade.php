<article class="card shadow-sm my-3 post" data-content-id="{{$post->content_id}}" >
    @auth
        @include('partials.content.header', [
        'content' => $post,
        'can_delete' => $post->author->user_id == $user->user_id || $user->admin,
        'can_edit' => $post->author->user_id == $user->user_id,
        'can_report' => true,
        'is_post' => true,
        ])
    @else
        @include('partials.content.header', ['content' => $post, 'can_delete' => false, 'can_edit' => false, 'can_report' => false, 'is_post' => true])
    @endauth

    <div class="card-body" @if (!$onPostPage) data-link-url="{{ $post->url }}" @endif>
        <p class="content-text card-text text-prewrap m-0 text-dark">{!! $post->formatted_content !!}</p>
        @if ($post->post->img != null)
        <img class="img-fluid mt-3" src="{{ $post->post->img }}" alt="Post image" />
        @endif
    </div>

    <div class="d-flex post-stats border-top">
        <span class="react-btn react-btn-upvote @auth clickable @endauth {{$post->is_liked ? "react-btn-highlight" : ""}}" data-content-id="{{$post->content_id}}"><i class="fas fa-thumbs-up"></i> <span class="p-0">{{$post->likes}}</span></span>
        <span class="react-btn react-btn-downvote @auth clickable @endauth {{$post->is_disliked ? "react-btn-highlight" : ""}}" data-content-id="{{$post->content_id}}"><i class="fas fa-thumbs-down"></i> <span class="p-0">{{$post->dislikes}}</span></span>
        <span class="btn-comments ml-auto" @if (!$onPostPage) data-link-url="{{ $post->url }}" @endif>
            <i class="fas fa-comment"></i> <span class="p-0">{{$post->post->comments}}</span>
        </span>
    </div>

    @if($onPostPage && $post->post->rules != null)
        @include('partials.content.rules_info', ['rules' => $post->post->rules])
    @endif
</article>