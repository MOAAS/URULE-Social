
<li data-user-id="{{$message->other->user_id}}" data-is-friends="{{Auth::user()->friends_with($message->other)}}" class="conversation-preview py-3 px-1 m-0 rounded-0 border-bottom border-black row align-items-center">
    <div class="col-2">
        <img class="profile-picture-small"
             src="{{$message->other->avatar}}"
                     alt="profilePic" />
    </div>
    <section class="col-7 text-truncate">
        <h3 class="m-0 text-truncate font-weight-bold conversation-username">{{$message->other->name}}</h3>
        <p class="m-0 text-truncate">
            @if($message->was_sent)
                @if($message->seen)
                    <i class="fas fa-check-circle"></i>
                @else
                    <i class="far fa-check-circle"></i>
                @endif
            @endif
            {{$message->content}}</p>
    </section>
    <small class="col-3 text-center">{{$message->timestamp}}</small>
</li>