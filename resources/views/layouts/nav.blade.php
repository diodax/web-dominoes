<nav class="navbar navbar-lg navbar-expand-lg navbar-dark {{ $navClass }}"> <!--style="background-color: #6200EE !important;"-->
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{-- {{ config('app.name', 'Laravel') }} --}}
            Web Dominoes
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Chat Room</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{ url('/') }}">Rules</a>
                </li>

                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    {{-- <li class="nav-item">
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </li> --}}
                @else
                    <div class="nav-item dropdown">
                            <button class="btn btn-outline-white dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              My account
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="#">Profile</a>
                              <a class="dropdown-item" href="{{ route('logout') }}"
                                      onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                                          {{ __('Sign out') }}
                                      </a>

                                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                            </div>
                    </div>
                @endguest
            </ul>

        </div>
    </div>
</nav>
