<tr class="super-alinhamento-3000">
    <td class="align-middle">{{$report->num_reports}}</td>
    <td class="align-middle small-hide">{{$report->content->content_id}}</td>
    <td class="text-truncate align-middle preview-col" data-link-url="{{ $report->content->url }}" >{{$report->content->content}}</td>
    <td class="xsmall-hide author-col p-1" >
    @include('partials.user.avatar', ['user' => $report->content->author])
    </td>
    <td class="align-middle" data-content-id="{{$report->content->content_id}}">
        <button class="btn btn-secondary btn-sm mr-1 reset-content-btn" title="Clear reports" data-content-id="{{$report->content->content_id}}"><i class="fas fa-brush fa-fw"></i></button>
        @if($report->content->post)
        <button class="btn btn-warning btn-sm mr-1 delete-content-btn xsmall-hide" title="Delete post" data-post-id="{{$report->content->content_id}}"><i class="fas fa-trash fa-fw"></i></button>
        @else
        <button class="btn btn-warning btn-sm mr-1 delete-content-btn  xsmall-hide" title="Delete comment" data-post-id="{{$report->content->comment->post_id}}" data-comment-id="{{$report->content->content_id}}"><i class="fas fa-trash fa-fw"></i></button>
        @endif

        @if ($report->content->author_id)
            @if($report->content->author->user_id == $user->user_id)
                <button class="btn btn-outline-secondary btn-sm" title="Ban user" disabled> <i class="fas fa-gavel fa-fw"></i></button>
            @elseif(!$report->content->author->ban)
                <button class="btn btn-danger btn-sm" title="Ban user"  data-toggle="modal" data-user-id="{{$report->content->author_id}}" data-target="#ban-user{{$report->content->author_id}}"> <i class="fas fa-gavel fa-fw"></i></button>
            @else
                <button class="btn btn-success btn-sm ml-auto mr-2"  title="Unban user" data-toggle="modal" data-target="#unban-user{{$report->content->author_id}}" data-user-id="{{$report->content->author_id}}"> <i class="fas fa-unlock fa-fw"></i></button>
            @endif
        @else
        <button class="btn btn-danger btn-sm" title="User no longer exists" disabled="disabled"><i class="fas fa-gavel fa-fw"></i></button>
        @endif
        @include('partials.admin.user_ban_modal', ['normal_user' => $report->content->author])
        @include('partials.admin.user_unban_modal', ['normal_user' => $report->content->author])
    </td>
</tr>

