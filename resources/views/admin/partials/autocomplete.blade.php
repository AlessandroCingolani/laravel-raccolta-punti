{{-- Componente autocomplete con parametri 1: rotta, 2: label, 3: API, 4: id --}}
<form method="GET" action="{{ route($route) }}" class="input-group">
    <input type="text" class="form-control" placeholder="{{ $label ?? 'Ricerca cliente' }}" autocomplete="off"
        aria-label="{{ $label ?? 'Ricerca cliente' }}" aria-describedby="button-addon2" name="tosearch"
        id="{{ $idInput }}">
    <button class="btn btn-primary" type="submit" id="button-addon2">Cerca</button>
</form>

<div id="{{ $idResults }}" class="autocomplete-results w-100 bg-white d-none position-absolute">
    {{-- Risultati dell'autocomplete --}}
</div>

<div id="{{ $idError }}" class="autocomplete-error text-danger position-absolute d-none"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let customersInput = document.getElementById('{{ $idInput }}');
        let resultsContainer = document.getElementById('{{ $idResults }}');
        let errorContainer = document.getElementById('{{ $idError }}');
        let toSearch = customersInput;
        let timeout = null;

        // Convertiamo i valori di Blade in booleani per JS
        let showEmail = {{ $email ? 'true' : 'false' }};
        let showCode = {{ $code ? 'true' : 'false' }};

        customersInput.addEventListener('input', function() {
            let searchValue = customersInput.value.trim();
            clearTimeout(timeout);

            timeout = setTimeout(() => {
                if (searchValue.length > 0) {
                    fetch('{{ $api }}' + searchValue)
                        .then(response => response.json())
                        .then(data => {
                            resultsContainer.innerHTML = '';
                            resultsContainer.classList.remove('d-none');

                            if (data && data.length > 0) {
                                data.forEach(result => {
                                    let resultItem = document.createElement('div');
                                    resultItem.classList.add('d-flex',
                                        'justify-content-between');

                                    let nameSurnameSpan = document.createElement(
                                        'span');
                                    let extraSpan = document.createElement('span');

                                    // Condizionale per visualizzare nome e cognome corretti
                                    if (showEmail) {
                                        nameSurnameSpan.textContent = result.name +
                                            ' ' + result.surname;
                                        extraSpan.textContent = result
                                        .email; // Mostra email se `$email` è true
                                    } else if (showCode) {
                                        nameSurnameSpan.textContent = result
                                            .recipient_first_name + ' ' + result
                                            .recipient_last_name;
                                        extraSpan.textContent = result
                                        .code; // Mostra codice se `$code` è true
                                    }

                                    // Aggiunge gli span al div del risultato
                                    resultItem.appendChild(nameSurnameSpan);
                                    resultItem.appendChild(extraSpan);
                                    resultsContainer.appendChild(resultItem);

                                    // Gestione del click per selezionare il risultato
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

        // Focusout per chiudere l'autocomplete
        document.addEventListener('click', function(event) {
            if (!resultsContainer.contains(event.target) && event.target !== toSearch) {
                resultsContainer.classList.add('d-none');
            }
        });
    });
</script>
