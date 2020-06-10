<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta property="og:title" content="@yield('title')" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:image" content="{{ asset('storage/brand.png') }}" />
    <meta property="og:description" content="This is a social network designed to allow users to create new relationships, making it easier to share moments with friends, and connecting people in a fun way." />
    <meta property="og:locale" content="{{ app()->getLocale() }}" />
    <meta property="og:type" content="{{ Route::currentRouteName() == "post" ? "article" : "website" }}" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <meta id="user-info" name="user-info" content="user-info"
         @isset($user)
         data-id="{{$user->user_id}}"
         data-name="{{$user->name}}"
         data-is-admin="{{$user->admin != null}}"
         @endisset
    >

    <!-- Styles -->

    @isset($css)
    <link rel="stylesheet" type="text/css" href="{{ asset('css/' . $css) }}">
    @endisset

    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" >
    <link href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" crossorigin="anonymous" rel="stylesheet" >

    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css" >

    <link rel="icon" href="{{ asset('storage/logo.png') }}">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />


    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="{{ asset('js/ajax.js') }}" defer></script>
    <script src="{{ asset('js/general.js') }}" defer></script>
    <script src="{{ asset('js/ux.js') }}" defer></script>

    @isset($js)
    <script src="{{ asset('js/' . $js) }}" defer></script>
    @endisset

    <script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
  </head>
  <body>
    @yield('body')
  </body>
</html>
