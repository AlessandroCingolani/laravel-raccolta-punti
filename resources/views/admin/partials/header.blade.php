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
                    <form method="GET" action="{{ route('admin.search-customer') }}" class="input-group">
                        <input type="text" class="form-control" placeholder="Ricerca cliente" autocomplete="off"
                            aria-label="Ricerca cliente" aria-describedby="button-addon2" name="tosearch"
                            id="tosearch">
                        <button class="btn btn-primary" type="submit" id="button-addon2">Cerca</button>
                    </form>
                    <div id="customers-results" class="w-100 bg-white d-none position-absolute">
                        {{-- autocomplete --}}
                    </div>

                    <div id="customers-error" class="text-danger position-absolute d-none"></div>
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
                                    <form method="GET" action="{{ route('admin.search-customer') }}"
                                        class="input-group">
                                        <input type="text" class="form-control" placeholder="Ricerca cliente"
                                            aria-label="Ricerca cliente" aria-describedby="button-addon2"
                                            name="tosearch" id="tosearch">
                                        <button class="btn btn-primary" type="submit" id="button-addon2">Cerca</button>
                                    </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let customersInput = document.getElementById('tosearch');
        let resultsContainer = document.getElementById('customers-results');
        let errorContainer = document.getElementById('customers-error');
        let toSearch = document.getElementById('tosearch');
        let timeout = null;

        customersInput.addEventListener('input', function() {
            let searchValue = customersInput.value.trim();
            console.log(searchValue);
            // clear timeout
            clearTimeout(timeout);

            // do call api only after 600 ms
            timeout = setTimeout(() => {
                if (searchValue.length > 0) {
                    //  AJAX
                    fetch('http://127.0.0.1:8000/api/auto-complete/' + searchValue)
                        .then(response => response.json())
                        .then(data => {

                            resultsContainer.innerHTML = '';
                            resultsContainer.classList.remove('d-none');

                            if (data && data.length > 0) {
                                data.forEach(result => {
                                    let resultItem = document.createElement('div');
                                    resultItem.classList.add('d-flex',
                                        'justify-content-between')

                                    let nameSurnameSpan = document.createElement(
                                        'span');
                                    nameSurnameSpan.textContent = result.name +
                                        ' ' + result.surname;

                                    let emailSpan = document.createElement('span');
                                    emailSpan.textContent = result.email;
                                    resultItem.appendChild(nameSurnameSpan);
                                    resultItem.appendChild(emailSpan);
                                    resultsContainer.appendChild(resultItem);
                                    resultItem.addEventListener('click',
                                        function() {
                                            toSearch.value = nameSurnameSpan
                                                .textContent;
                                            resultsContainer.innerHTML = '';
                                            errorContainer.textContent = '';
                                            resultsContainer.classList.add(
                                                'd-none');
                                        });
                                });
                                errorContainer.textContent = '';
                                errorContainer.classList.add('d-none');

                            } else {
                                errorContainer.classList.remove('d-none');
                                errorContainer.textContent = 'Nessun risultato trovato.';
                            }
                        })
                        .catch(error => {
                            console.error('Errore nella richiesta:', error);
                            errorContainer.textContent =
                                'Errore nella ricerca. Riprovare più tardi.';
                            resultsContainer.classList.add('d-none');

                        });
                } else {
                    resultsContainer.innerHTML = '';
                    errorContainer.textContent = '';
                    errorContainer.classList.add('d-none');
                    resultsContainer.classList.add('d-none');
                }
            }, 600);

        });
        //TODO: focusout to close autocomplete
    });
</script>
