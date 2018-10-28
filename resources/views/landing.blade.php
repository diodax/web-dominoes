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
            <p class="lead mb-3">Play dominoes with other players. The theme is fully customizable & can be used for any type of application.</p>
            <a class="btn btn-success btn-lg mr-lg-2 my-1" href="{{ route('register') }}" role="button">Sign up</a>
            <a class="btn btn-outline-white btn-lg my-1" href="#" role="button">How to play?</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <main class="main" role="main">

    <div class="bg-white py-7">
      <div class="container">
        <div class="row">
          <div class="col-md-10 mx-auto">
            <div class="row">
              <div class="col-md-4 ml-auto">
                <h2>Creative & flexible Bootstrap UI Kit</h2>
              </div>
              <div class="col-md-6 mr-auto">
                <p class="lead text-dark">
                  Robust includes various demo pages for building your custom app, blog or landing page. All code is handwritten, all our components are optimized for desktop, tablet and mobile.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-md-4">
            <div class="media">
              <div class="icon mr-3">
                <i class="far fa-id-badge"></i>
              </div>
              <div class="media-body">
                <h3 class="h4">Bootstrap 4</h3>
                <p class="text-dark text-left">
                  With mobile, tablet & desktop support it doesn't matter what device you're using. Robust is responsive in all browsers.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="media">
              <div class="icon mr-3">
                <i class="far fa-hand-scissors"></i>
              </div>
              <div class="media-body">
                <h3 class="h4">Mobile friendly</h3>
                <p class="text-dark text-left">
                  Robust works perfectly with: Chrome, Firefox, Safari, Opera and IE 10+. We're working hard to support them.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="media">
              <div class="icon mr-3">
                <i class="far fa-comments"></i>
              </div>
              <div class="media-body">
                <h3 class="h4">Premium support</h3>
                <p class="text-dark text-left">
                  Robust is supported by specialists who provide quick and effective support. Usually an email reply takes &lt;24h.
                </p>
              </div>
            </div>
          </div>
        </div><!-- /.row -->
      </div>
    </div>

    <div class="py-6 bg-white">
      <div class="container">

        <div class="row mb-6">
          <div class="col-md-3 ml-auto">
            <h2>Features included in every plan</h2>
          </div>
          <div class="col-md-5 mr-auto">
            <p class="lead text-dark">
              Robust is a theme built with Bootstrap 4, the popular UI framework. The theme is responsive and can be used for any type of web app.
            </p>
          </div>
        </div>

        <div class="row mt-5">
          <div class="col-md-4">
            <div class="media">
              <div class="icon mr-3">
                <i class="far fa-id-badge"></i>
              </div>
              <div class="media-body">
                <h3 class="h4">Responsive</h3>
                <p class="text-dark text-left">
                  With mobile, tablet & desktop support it doesn't matter what device you're using. Robust is responsive in all browsers.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="media">
              <div class="icon mr-3 bg-warning">
                <i class="far fa-hand-scissors"></i>
              </div>
              <div class="media-body">
                <h3 class="h4">Customizable</h3>
                <p class="text-dark text-left">
                  You don't need to be an expert to customize Robust. Our code is very readable and well documented.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="media">
              <div class="icon mr-3 bg-danger">
                <i class="far fa-comments"></i>
              </div>
              <div class="media-body">
                <h3 class="h4">Quick support</h3>
                <p class="text-dark text-left">
                  Robust is supported by specialists who provide quick and effective support. Usually an email reply takes &lt;24h.
                </p>
              </div>
            </div>
          </div>
        </div><!-- /.row -->

        <div class="row mt-5">
          <div class="col-md-4">
            <div class="media">
              <div class="icon mr-3 bg-success">
                <i class="far fa-clone"></i>
              </div>
              <div class="media-body">
                <h3 class="h4">Cross browser</h3>
                <p class="text-dark text-left">
                  Robust works perfectly with: Chrome, Firefox, Safari, Opera and IE 10+. We're working hard to support them.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="media">
              <div class="icon mr-3 bg-purple">
                <i class="far fa-gem"></i>
              </div>
              <div class="media-body">
                <h3 class="h4">Clean code</h3>
                <p class="text-dark text-left">
                  We strictly followed Bootstrap's guidelines to make your integration as easy as possible. All code is handwritten.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="media">
              <div class="icon mr-3">
                <i class="far fa-arrow-alt-circle-down"></i>
              </div>
              <div class="media-body">
                <h3 class="h4">Free updates</h3>
                <p class="text-dark text-left">
                  From time to time you'll receive an update containing new components, improvements and bugfixes.
                </p>
              </div>
            </div>
          </div>
        </div><!-- /.row -->
      </div>
    </div>

    <div class="py-6 bg-dark text-white">
      <div class="container">
        <div class="row">
          <div class="col-md-10 mx-auto">
            <div class="row">
              <div class="col-md-4 ml-auto">
                <h2>Who we are<br /> working with</h2>
              </div>
              <div class="col-md-6 mr-auto">
                <p class="lead text-light">
                  Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Sed aliquam ultrices mauris. Integer ante arcu, accumsan.
                </p>
              </div>
            </div>
            <div class="row align-items-center my-md-4 text-center">
              <div class="col">
                <i class="fab fa-4x fa-microsoft"></i>
              </div>
              <div class="col">
                <i class="fab fa-4x fa-github"></i>
              </div>
              <div class="col">
                <i class="fab fa-4x fa-ebay"></i>
              </div>
              <div class="col">
                <i class="fab fa-4x fa-apple"></i>
              </div>
              <div class="col">
                <i class="fab fa-4x fa-amazon"></i>
              </div>
              <div class="col">
                <i class="fab fa-4x fa-stripe"></i>
              </div>
            </div>
          </div>
        </div>
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
