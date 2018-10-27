    <nav class="navbar navbar-lg navbar-expand-lg navbar-dark bg-info">
            <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                  <!-- Authentication Links -->
                  @guest
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                  </li>
                  <li class="nav-item">
                      @if (Route::has('register'))
                          <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                      @endif
                  </li>
              @else
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          {{ Auth::user()->username }} <span class="caret"></span>
                      </a>

                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="{{ route('logout') }}"
                             onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                              {{ __('Logout') }}
                          </a>

                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              @csrf
                          </form>
                      </div>
                  </li>
              @endguest
                </ul>
                {{-- <div class="dropdown">
                  <button class="btn btn-outline-white dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    My account
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Profile</a>
                    <a class="dropdown-item" href="#">Analytics</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Settings &amp; Privacy</a>
                    <a class="dropdown-item" href="#">Help</a>
                    <a class="dropdown-item" href="#">Sign out</a>
                  </div>
                </div> --}}
              </div>
            </div>
          </nav>
