@extends('layouts.main', ['css' => 'search.css', 'js' => 'search.js', 'nav' => 'search'])

@php
    if ($keywords == null)
        $pageTitle = "Search";
    else if (strlen($keywords) > 30)
        $pageTitle = substr("$keywords", 0, 27) . "... - Search";
    else $pageTitle = $keywords . " - Search"
@endphp

@section('title', $pageTitle)

@section('content')
<section class="p-0 col-sm-10 page-section">
    @include('partials.navbars.topbar', [
        'user' => $user,
        'nav' => 'search'
    ])

    <form id="search-form" method="get" action="{{ route('search') }}" >
        <div class="input-group">
            <div class="input-group-prepend"><i class="fas fa-search input-group-text font-weight-bold"></i></div>        
            <input type="text" class="form-control" placeholder="Search for posts/people..." value="{{ $keywords }}" aria-label="Search" name="keywords" id="searchQuery">
        </div>

        <div id="search-settings" class="toggleable-settings hide-settings">
            <div class="form-check">
                <input id="person-filter" value="true" class="form-check-input" type="checkbox" name="users" {{ $showUsers == "true" ? 'checked' : '' }}>
                <label class="form-check-label" for="person-filter">Person</label>
            </div>  
            
            <div class="form-check">
                <input id="post-filter" value="true" class="form-check-input" type="checkbox" name="posts" {{ $showPosts == "true" ? 'checked' : '' }}>
                <label class="form-check-label" for="post-filter">Post</label>
            </div>

            <div class="row my-2" id="dates">
                <div class="col-12 col-md-6 input-group p-0 pr-2">
                    <label for="startDate" class="input-group-prepend"><span class="input-group-text">From</span></label>
                    <input id="startDate" type="date" class="form-control" name="startDate" value='{{$startDate}}'>
                </div>
                <div class="col-12 col-md-6 input-group p-0 pl-2">
                    <label for="endDate" class="input-group-prepend"><span class="input-group-text">To</span></label>
                    <input id="endDate" type="date" class="form-control" name="endDate" value='{{$endDate}}'>
                </div>
            </div>

            <div class="border-top mt-4"></div>
        </div>

        @include('partials.settings_toggler', ['label' => 'Advanced', 'target' => '#search-settings'])

        <input id="search-btn" type="submit" class="btn btn-primary" value="Search">
    </form>

    <div id="resultsDiv" class="centered-content">
        @forelse ($results as $result)
            @isset($result->content_id)
                @include('partials.search.content_result', ['content' => $result])
            @endisset
            @isset($result->user_id)
                @include('partials.search.user_result', ['user' => $result])
            @endisset
        @empty
            @if ($isQuery)
            <div class="container text-center mt-5 h3">No results found.</div>
            @endif
        @endforelse

    </div>

    @if (count($results) > 5)
        @include("partials.spinner")
    @endif
</section>
@endsection