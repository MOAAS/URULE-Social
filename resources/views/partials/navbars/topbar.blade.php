<header id="top-bar" class="sticky-top">
    @auth
        <button id="hamburger-btn" class="btn"><i class="fas fa-bars fa-1x"></i></button>
    @else
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn d-sm-none mr-2 unselected"><i class="fas fa-sign-out-alt"></i></button>
        </form>
    @endauth
    <h2 id="page-title" class="text-smart-break">@yield('title')</h2>
</header>    

@auth
    <nav id="collapsable-sidebar">
        <div id="collapsable-sidebar-menu" class="d-flex flex-column justify-content-between list-group-flush">
            <div>
                <div class="d-flex">
                    @include('partials.user.avatar', ['user' => $user])
                    <button id="hamburger-btn-close" class="btn"><i class="fas fa-times"></i></button>
                </div>

                <a class="list-group-item list-group-item-action {{$nav=='feed'? 'selected':''}}" href="{{ route('feed') }}"><i class="fa fa-home fa-lg fa-fw"></i><span>&nbsp; News Feed</span></a>
                <a class="list-group-item list-group-item-action {{$nav=='profile'?'selected':''}}" href="{{ $user->url }}"><i class="fa fa-user fa-lg fa-fw"></i><span>&nbsp; Profile</span></a>
                <a class="list-group-item list-group-item-action {{$nav=='friend_requests'? 'selected':''}}" href="{{ route('friends.requests') }}"><i class="fa fa-user-plus fa-lg fa-fw"></i><span>&nbsp; Friend Requests</span></a>

                @php
                    $notifications = \App\Notification::get_notifications()->get();
                @endphp

                <div class="w-100 border-top">
                    <button type="button" class="notifications-btn list-group-item list-group-item-action" data-toggle="collapse" data-target="#collapsable-notifications">
                        <i class="fa fa-bell fa-lg fa-fw"></i><span>&nbsp; Notifications</span>
                        <span class="num-notifications badge badge-danger ml-1">{{ $notifications->count() }}</span>
                    </button>
                    <div id="collapsable-notifications" class="collapse bg-white">
                        @each('partials.content.notification', $notifications, 'notification')
                    </div>
                </div>
               
                @if ($user->admin)
                    <a class="list-group-item list-group-item-action border-bottom {{$nav=='admin'? 'selected':''}}" href="{{ route('admin') }}"><i class="fa fa-toolbox fa-lg fa-fw"></i><span>&nbsp; Admin Page</span></a>         
                @endif
            </div>
            <div>
                <a class="list-group-item list-group-item-action border-top {{$nav=='about'? 'selected':''}}" href="{{ route('about') }}"><i class="fa fa-question-circle fa-lg fa-fw"></i><span>&nbsp; About</span></a>
                <form class="border-top" method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action"><i class="fa fa-sign-out-alt fa-lg fa-fw"></i><span>&nbsp; Sign out</span></button>
                </form>
            </div>
        </div>
    </nav>
@endauth
