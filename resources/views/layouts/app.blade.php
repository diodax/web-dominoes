<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/robust.css') }}" rel="stylesheet">
        <style type="text/css">.medium-zoom-overlay{position:fixed;top:0;right:0;bottom:0;left:0;opacity:0;transition:opacity .3s;will-change:opacity}.medium-zoom--open .medium-zoom-overlay{cursor:pointer;cursor:zoom-out;opacity:1}.medium-zoom-image{cursor:pointer;cursor:zoom-in;transition:transform .3s}.medium-zoom-image--open{position:relative;cursor:pointer;cursor:zoom-out;will-change:transform}</style>
    </head>
    <body>
        @include('layouts.nav', ['navClass' => 'bg-primary'])

        <main class="main" role="main">
            <div class="py-5 bg-light">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </main>

        @include('layouts.footer')
        <!-- Scripts -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="{{ url('/js/jquery-3.3.1.slim.min.js') }}"></script>
        <script src="{{ url('/js/popper.min.js') }}"></script>
        <script src="{{ url('/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/robust.js') }}"></script>
    </body>
</html>
