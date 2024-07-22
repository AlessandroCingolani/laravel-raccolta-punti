<header>
    <nav class="shadow-sm h-100">
        <div class="container h-100">
            <div class="row w-100 h-100 align-items-center justify-content-between">
                {{-- Logo --}}
                <div class="col-3">
                    <a href="{{ url('/') }}">LOGO CLIENTE</a>
                </div>
                {{-- search client bar --}}
                <div class="d-none d-md-block col-md-6">
                    <form method="GET" action="{{ route('admin.search-customer') }}" class="input-group">
                        <input type="text" class="form-control" placeholder="Ricerca cliente"
                            aria-label="Ricerca cliente" aria-describedby="button-addon2" name="tosearch"
                            id="tosearch">
                        <button class="btn btn-primary" type="submit" id="button-addon2">Cerca</button>
                    </form>
                </div>



                <div class="col-3">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown ms-md-auto">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('dashboard') }}">{{ __('Dashboard') }}</a>
                                    <a class="dropdown-item" href="{{ url('profile') }}">{{ __('Profile') }}</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>

            </div>
        </div>
    </nav>


</header>
