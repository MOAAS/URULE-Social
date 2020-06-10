@extends('layouts.app', ['css' => isset($css) ? $css : null, 'js' => isset($js) ? $js : null])

@section('body')
    <div class="row">
        @include('partials.navbars.sidebar', ['user' => $user, 'nav' => $nav])

        @yield('content')

        @include('partials.navbars.botbar', ['nav' => $nav])

    </div>
@endsection
