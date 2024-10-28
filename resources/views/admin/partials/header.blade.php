<header>
    <nav class="shadow-sm h-100">
        <div class="container h-100">
            <div class="row w-100 h-100 align-items-center justify-content-between">
                {{-- Logo --}}
                <div class="col-3">
                    <a href="{{ url('/') }}">LOGO CLIENTE</a>
                </div>
                {{-- search client bar --}}
                {{-- 1 rotta  2 label 3 Api 4 email/codice --}}
                <div class="d-none d-md-block col-md-6 position-relative">
                    {{-- componente autocomplete qui --}}
                    @include('admin.partials.autocomplete', [
                        'idInput' => 'searchHeader',
                        'idResults' => 'resultsHeader',
                        'idError' => 'errorHeader',
                        'route' => 'admin.search-customer',
                        'label' => 'Ricerca cliente',
                        'api' => 'http://127.0.0.1:8000/api/auto-complete/',
                        'email' => true,
                        'code' => false,
                    ])
                </div>


                {{-- mobile  search bar  --}}
                <div class="col-3 d-md-none d-flex justify-content-center">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><i
                            class="fa-solid fa-bars"></i></button>

                    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop"
                        aria-labelledby="offcanvasTopLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasTopLabel">Menu mobile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <div class="row">
                                <div class="col-6">
                                    @include('admin.partials.autocomplete', [
                                        'idInput' => 'searchHeaderMobile',
                                        'idResults' => 'resultsHeaderMobile',
                                        'idError' => 'errorHeaderMobile',
                                        'route' => 'admin.search-customer',
                                        'label' => 'Ricerca cliente',
                                        'api' => 'http://127.0.0.1:8000/api/auto-complete/',
                                        'email' => true,
                                        'code' => false,
                                    ])
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-3 text-center">
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
