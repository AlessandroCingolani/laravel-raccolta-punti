{{-- Componente autocomplete con parametri 1: rotta, 2: label, 3: API, 4: campo ricerca --}}
<form method="GET" action="{{ route($route) }}" class="input-group">
    <input type="text" class="form-control" placeholder="{{ $label ?? 'Ricerca cliente' }}" autocomplete="off"
        aria-label="{{ $label ?? 'Ricerca cliente' }}" aria-describedby="button-addon2" name="tosearch" id="tosearch">
    <button class="btn btn-primary" type="submit" id="button-addon2">Cerca</button>
</form>
<div id="customers-results" class="w-100 bg-white d-none position-absolute">
    {{-- autocomplete --}}
</div>

<div id="customers-error" class="text-danger position-absolute d-none"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let customersInput = document.getElementById('tosearch');
        let resultsContainer = document.getElementById('customers-results');
        let errorContainer = document.getElementById('customers-error');
        let toSearch = document.getElementById('tosearch');
        let timeout = null;

        customersInput.addEventListener('input', function() {
            let searchValue = customersInput.value.trim();
            clearTimeout(timeout);

            timeout = setTimeout(() => {
                if (searchValue.length > 0) {
                    // Utilizza l'URL API dal parametro "api"
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

        // Focusout per chiudere l'autocomplete
        document.addEventListener('click', function(event) {
            if (!resultsContainer.contains(event.target) && event.target !== toSearch) {
                resultsContainer.classList.add('d-none');
            }
        });
    });
</script>
