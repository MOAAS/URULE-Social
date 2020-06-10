<li data-message-id="{{$message->message_id}}" class="my-1 mx-3 p-2 {{$message->was_sent?"message-sent":"message-received"}}">
    <i class="delete-msg clickable fa fa-trash" aria-hidden="true"></i>
    <p class="m-0">{{$message->content}}</p>
</li>