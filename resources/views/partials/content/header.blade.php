<header class="p-2 card-title bg-light d-flex align-items-center m-0">
    @include('partials.user.avatar', ['user' => $content->author])
    <small class="ml-auto mr-2 text-muted">{{ $content->content_date_short }}</small>

    @if ($can_edit || $can_delete || $can_report)
        <div class="dropleft p-2">
            <button class="btn text-dark" data-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></button>
            <div class="dropdown-menu">
                @if ($can_edit)
                    <button class="dropdown-item" data-toggle="modal" data-target="#edit-content{{$content->content_id}}">Edit</button>
                @endif

                @if ($can_delete)
                    <button class="dropdown-item" data-toggle="modal" data-target="#delete-content{{$content->content_id}}">Delete</button>
                @endif

                @if ($can_report)
                    <button class="dropdown-item report-content-btn" data-content-id="{{$content->content_id}}">Report</button>
                @endif
            </div>
        </div>

        @include('partials.content.edit_modal', ['is_post' => $is_post, 'content_id' => $content->content_id, 'content' => $content->content])
        @include('partials.content.delete_modal', ['is_post' => $is_post, 'content_id' => $content->content_id])
    @endif
</header>
