<nav id="bottom-bar" class="navbar fixed-bottom bg-light border-top">
    <div class="btn navbar-brand {{ $nav=='search'?'selected':'unselected'}}">
        <a href="{{ route('search') }}"><i class="fa fa-search"></i></a>
    </div>
    
    <div class="btn navbar-brand {{ $nav=='hot'?'selected':'unselected'}}">
        <a href="{{ route('hot') }}"><i class="fa fa-fire"></i></a>
    </div>
    
    @auth
        <div class="btn navbar-brand {{ $nav=='messages'?'selected':'unselected'}}">
            <a href="{{ route('messages') }}"><i class="fa fa-envelope"></i></a>
        </div>
    @else
        <div class="btn navbar-brand {{ $nav=='about'?'selected':'unselected'}}">
            <a href="{{ route('about') }}"><i class="fa fa-question-circle"></i></a>
        </div>
    @endauth
</nav>   