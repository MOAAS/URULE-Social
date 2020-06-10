<header class="px-3 mb-1 sticky-top border-bottom bg-light d-flex align-items-center">
    <div class="d-flex align-items-center">
        @if($selectedUser != null)
            <button id="back-btn" class="btn"><i class="fas fa-arrow-left"></i></button>
            <img class="profile-picture-xsmall" src="{{$selectedUser->avatar}}" alt="profilePic" />
            <h2 class="py-3 m-0 text-truncate">{{$selectedUser->name}}</h2>
        @endif
    </div>
</header>
<ul id="message-list" class="p-0 m-0 flex-grow-1 overflow-auto">
    @if($selectedUser != null)
        @foreach($selectedUserMessages as $message)
            @include('partials.messages.message', ['message' => $message])
        @endforeach
    @endif
</ul>
<form id="send-msg-form" {{$selectedUser ? "data-user-id=".$selectedUser->user_id : ""}} class="d-flex m-0 p-3 border-top align-items-end">
    <!--button class="btn btn-outline-primary rounded-circle"><i class="fa fa-plus"></i></button-->
    <textarea name="content" class="flex-grow-1 mr-3 form-control" rows="1" placeholder="Send a message..." aria-label="Message" disabled="disabled"></textarea>
    <button type="submit" class="btn btn-primary" disabled="disabled"><i class="fa fa-arrow-right"></i></button>
</form>