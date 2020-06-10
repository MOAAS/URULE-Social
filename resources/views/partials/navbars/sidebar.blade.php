<div id="sidebar" class="col-2 sticky-top bg-light border-right d-flex flex-column vh-100 sidebar-wrapper p-0">
    <div class="sidebar-heading">
        @include ('partials.brand.small')
    </div>

    <nav class="d-flex flex-column justify-content-between list-group list-group-flush flex-grow-1">
        <div>
            @include('partials.user.avatar', ['user' => $user])

            <a class="list-group-item list-group-item-action {{$nav=='hot'? 'selected':''}}" href="{{ route('hot') }}"><i class="fas fa-fire fa-lg fa-fw"></i><span>&nbsp; Hot Page</span></a>

            @auth
                <a class="list-group-item list-group-item-action {{$nav=='feed'? 'selected':''}}" href="{{ route('feed') }}"><i class="fa fa-home fa-lg fa-fw"></i><span>&nbsp; News Feed</span></a>
                <a class="list-group-item list-group-item-action {{$nav=='profile'? 'selected':''}}" href="{{ $user->url }}"><i class="fa fa-user fa-lg fa-fw"></i><span>&nbsp; Profile</span></a>
                <a class="list-group-item list-group-item-action {{$nav=='messages'? 'selected':''}}" href="{{ route('messages') }}"><i class="fa fa-envelope fa-lg fa-fw"></i><span>&nbsp; Messages</span></a>
                <a class="list-group-item list-group-item-action {{$nav=='friend_requests'? 'selected':''}}" href="{{ route('friends.requests') }}"><i class="fa fa-user-plus fa-lg fa-fw"></i><span>&nbsp; Friend Requests</span></a>

                @php
                    $notifications = \App\Notification::get_notifications()->get();
                @endphp


                <div class="btn-group dropright w-100" >
                    <button type="button" class="notifications-btn list-group-item list-group-item-action dropdown-toggle" data-toggle="dropdown">
                        <i class="unselected fa fa-bell fa-lg fa-fw"></i><span>&nbsp; Notifications</span>
                        <small class="num-notifications badge badge-danger ml-1">{{ $notifications->count() }}</small>
                    </button>
                    <div id="dropdown-notifications" class="shadow dropdown-menu bg-white">
                        @forelse($notifications as $notification)
                            @include('partials.content.notification', ['notification' => $notification])
                        @empty
                            <p class="my-3 text-center h4">No notifications.</p>
                        @endforelse
                    </div>
                </div>
            @endauth

            <a class="list-group-item list-group-item-action {{$nav=='search'? 'selected':''}} border-bottom" href="{{ route('search') }}"><i class="fa fa-search fa-lg fa-fw"></i><span>&nbsp; Search</span></a>


            @if ($user != null && $user->admin)
                <a class="list-group-item list-group-item-action {{$nav=='admin'? 'selected':''}} border-bottom" href="{{ route('admin') }}"><i class="fa fa-toolbox fa-lg fa-fw"></i><span>&nbsp; Admin Page</span></a>
            @endif
        </div>
        <div>
            <a class="list-group-item list-group-item-action {{$nav=='about'? 'selected':''}} border-top" href="{{ route('about') }}"><i class="fa fa-question-circle fa-lg fa-fw"></i><span>&nbsp; About</span></a>

            @auth
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action"><i class="fa fa-sign-out-alt fa-lg fa-fw"></i><span>&nbsp; Sign Out</span></button>
                </form>
            @else
                <a class="list-group-item list-group-item-action" href="{{ route('login') }}"><i class="fa fa-sign-in-alt fa-lg fa-fw"></i><span>&nbsp; Sign In</span></a>
            @endauth
        </div>
    </nav>
</div>
