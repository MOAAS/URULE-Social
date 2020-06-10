@extends('layouts.main', ['css' => 'admin.css', 'js' => 'admin.js', 'nav' => 'admin'])

@section('content')
    <section id="admin-page" class="p-0 col-sm-10 page-section">
        @include('partials.navbars.topbar', ['user' => $user, 'nav' => 'admin'])
        <div class="centered-content mt-2">
            @include('partials.navbars.adminbar',['selected' => $admin_tab])

            @yield('content_admin')
        </div>
    </section>
@endsection