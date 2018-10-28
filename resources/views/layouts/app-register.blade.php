<!doctype html>
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
        <link href="{{url('/css/robust.css')}}" rel="stylesheet">
    </head>
    <body class="bg-light">

        <main class="main h-100" role="main">
            <div class="container h-100">
                @yield('content')
            </div>
        </main>

        <!-- Scripts -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="{{ url('/js/jquery-3.3.1.slim.min.js') }}"></script>
        <script src="{{ url('/js/popper.min.js') }}"></script>
        <script src="{{ url('/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/bundle.js') }}"></script>
    </body>
</html>
