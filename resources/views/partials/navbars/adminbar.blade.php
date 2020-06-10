<nav class="list-group list-group-horizontal mb-4">
    <a class="list-group-item list-group-item-action {{$selected=='announcements'? 'active':''}} text-center flex-fill" href="{{ route('admin.announcements') }}">
        <i class="fa fa-bullhorn fa-fw"></i>
        <span class="small-hide"> Announcements</span>
    </a>
    <a class="list-group-item list-group-item-action {{$selected=='users'? 'active':''}} text-center flex-fill" href="{{ route('admin.users') }}">
        <i class="fa fa-users fa-fw"></i>
        <span class="small-hide"> Users</span>
    </a>
    <a class="list-group-item list-group-item-action {{$selected=='reports'? 'active':''}} text-center flex-fill" href="{{ route('admin.reports') }}">
        <i class="fa fa-flag fa-fw"></i>
        <span class="small-hide"> Content Reports</span>
    </a>
</nav>