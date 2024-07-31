<div class="col-auto">
    <div class="btn btn-success d-flex align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
        <i class="fa-solid fa-filter "></i>
        <span class="d-none d-md-block ps-2">Filtri</span>
    </div>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Seleziona filtri</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="form-filter" action="{{ $route }}" method="GET" enctype="multipart/form-data">
            <div class="fw-bold my-2 w-100">
                <label for="purchaseRange">Seleziona l'importo minimo da cercare:</label>
                <input type="range" id="purchaseRange" name="purchaseRange" min="0" max="5000"
                    step="100" value="0" oninput="updateValue(this.value)">
                <span id="rangeValue">0</span> €
            </div>
            <div class="fw-bold my-2 w-100">
                <div class="mb-2">
                    <label for="customCheckbox">Mostra solo i clienti con coupon:</label>
                </div>
                <label class="checkbox-wrapper">
                    {{-- hidden input to send at route --}}
                    <input type="hidden" id="checkboxHiddenValue" name="coupons" value="false">
                    <input type="checkbox" class="checkbox-input" id="customCheckbox" />
                    <span class="checkbox-tile">
                        <span class="checkbox-icon">
                            <i class="fa-solid fa-ticket"></i>
                        </span>
                        <span class="checkbox-label"></span>
                    </span>
                </label>
            </div>
            <button class="btn btn-success mt-3" onclick="submitForm()">Cerca</button>
        </form>
    </div>
</div>


<script>
    function updateValue(val) {
        document.getElementById('rangeValue').textContent = val;
        console.log(purchaseRange.value);
    }

    function submitForm() {

        let isChecked = document.getElementById('customCheckbox').checked;


        document.getElementById('checkboxHiddenValue').value = isChecked ? "true" : "false";

        document.getElementById('form-filter').submit();
    }
</script>
