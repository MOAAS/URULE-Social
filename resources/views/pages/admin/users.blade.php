@extends('layouts.admin', ['admin_tab' => 'users'])

@section('title', 'Admin - Users')

@section('content_admin')
    <form method="get" class="mb-3" id="search-users-form" action="{{route('admin.users')}}">

        <div class="input-group mt-3 mb-2">
            <div class="input-group-prepend"><i class="fas fa-search input-group-text font-weight-bold"></i></div>
            <input type="text" class="form-control" name="query" value="{{ $query }}" placeholder="Search for users username or id" aria-label="Search">
        </div>
        <div class="d-flex justify-content-between">
            <div class="form-check">
              <input class="form-check-input " type="checkbox" value="" id="defaultCheck1" name="banned" {{ $banned ? 'checked' : ''}}>
              <label class="form-check-label" for="defaultCheck1">
                Banned users only
              </label>
             </div>
            <input id="search-button" type="submit" class="btn btn-primary px-2 py-1 " value="Search">
        </div>
    </form>

    <ul class="list-group mt-3 mb-3">
        <li class="list-group-item list-group-item-dark d-flex align-items-center justify-content-between ">
            <span class="number font-weight-bold">#</span>
            <span class="name text-truncate ml-4 font-weight-bold">Profile</span>
            <span class="ml-auto mr-2 font-weight-bold">Action</span>
        </li>
        @forelse($users as $normal_user)
            @include('partials.admin.users_list_item', ['normal_user' => $normal_user])
        @empty
            <div class="container text-center mt-5 h5 no-reports">No users were found.</div>
        @endforelse
    </ul>

    <div class="row d-flex justify-content-center">
        {{$users->links()}}
    </div>
@endsection
