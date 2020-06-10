@extends('layouts.admin', ['admin_tab' => 'announcements'])

@section('title', 'Admin - Announcements')

@section('content_admin')
    @include('partials.admin.announcement_form')

    <h5> Active announcements </h5>
    <div id="announcement-list">
    @forelse (\App\Announcement::active_announcements()->get() as $announcement)
        @include('partials.admin.announcement_admin', ['announcement' => $announcement])
    @empty
        <div class="container text-center mt-5 h5 no-announcements">No active announcements</div>
    @endforelse
    </div>
@endsection