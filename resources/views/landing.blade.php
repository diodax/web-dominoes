<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ asset('css/robust.css') }}" rel="stylesheet">
    </head>
<body>
    @include('layouts.nav', ['navClass' => 'navbar-transparant navbar-absolute w-100'])

  <div class="intro py-5 py-lg-9 position-relative text-white">
    <div class="bg-overlay-primary">
      <img src="{{ url('/img/dominoes-table-background.jpg') }}" class="img-fluid img-cover" alt="Robust UI Kit" />
    </div>
    <div class="intro-content py-6 text-center">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12 col-sm-10 col-md-8 col-lg-6 mx-auto text-center">
            <h1 class="my-3 display-4 d-none d-lg-inline-block">Web Dominoes</h1>
            <span class="h1 my-3 d-inline-block d-lg-none">Web Dominoes</span>
            <p class="lead mb-3">Play dominoes with other players.</p>

            @guest
                <a class="btn btn-success btn-lg mr-lg-2 my-1" href="{{ route('register') }}" role="button">Sign up</a>
                <a class="btn btn-outline-white btn-lg my-1" href="{{ route('login') }}" role="button">Log in</a>
            @else
                <a class="btn btn-outline-white btn-lg mr-lg-2 my-1" href="{{ route('home') }}" role="button">Open Chat Room</a>
            @endguest



          </div>
        </div>
      </div>
    </div>
  </div>

  @include('layouts.footer')

  <!-- Scripts -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="{{ url('/js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ url('/js/popper.min.js') }}"></script>
        <script src="{{ url('/js/bootstrap.min.js') }}"></script>
</body>
</html>
