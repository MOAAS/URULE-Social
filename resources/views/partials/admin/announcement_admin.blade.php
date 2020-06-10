<article class="card shadow-sm mx-md-3 my-3 announcement" data-announcement-id="{{$announcement->announcement_id}}">
    <header class="p-0 card-title bg-light d-flex align-items-center m-0">
        @include('partials.user.avatar', ['user' => $announcement->author->user])
	    <small class="ml-auto mr-2 text-muted d-none d-sm-inline">{{ $announcement->time_left }} left</small>
        <button class="btn text-dark delete-announcement-btn ml-auto ml-sm-0" data-toggle="dropdown" data-announcement-id="{{$announcement->announcement_id}}"> <i class="fas fa-trash"></i></button>
    </header>
    <div class="card-body">    
        <p class="card-text text-prewrap m-0">{{ $announcement->content }}</p>
    </div>
</article>