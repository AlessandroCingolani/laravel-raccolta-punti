@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <h2>{{ $title }}</h2>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        @dump($customer_selected)
        @dump($coupons)
        @dump($customers_name)
        {{-- TODO: style --}}
        <div class="mb-3">
            <a href="{{ route('admin.customers.create') }}">Aggiungi nuovo cliente
            </a>
        </div>
        <form id="form-purchase" action="{{ $route }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method($method)
            <div class="row justify-content-between">
                <div class="col-4">
                    <div class="mb-3">
                        <label for="id" class="control-label">Nome Cliente *</label>
                        <select {{ isset($purchase) ? 'disabled' : '' }} id="customer-select"
                            class="form-select @error('id') is-invalid @enderror" name="id" autocomplete="id"
                            type="text">
                            <option value="">Seleziona cliente</option>
                            @forelse ($customers_name as $name)
                                <option
                                    {{ $customer_selected?->id === $name->id || old('id', $purchase?->customer_id) === $name->id ? 'selected' : '' }}
                                    value="{{ $name->id }}">{{ $name->name . ' ' . $name->surname }} </option>
                            @empty
                                <option value="">Nessun cliente</option>
                            @endforelse

                        </select>
                        @error('id')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Pagamento Cliente *</label>
                        <input id="amount" class="form-control @error('amount') is-invalid @enderror" name="amount"
                            value="{{ old('ampunt', $purchase?->amount) }}" step=0.01 placeholder="importo €"
                            autocomplete="amount" type="number">

                        @error('amount')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <button id="btn-submit" type="submit" class="btn btn-primary">{{ $button }}</button>
                    <button id="btn-reset" type="reset" class="btn btn-secondary">Annulla</button>
                </div>

                @if (!isset($purchase))
                    <div class="col-4">
                        <h2>{{ !is_null($customer_selected?->name) ? ($coupons > 0 ? $customer_selected->name . ' ' . $customer_selected->surname . ' ha ' . $coupons . ' coupons disponibili' : $customer_selected->name . ' ' . $customer_selected->surname . ' non ha coupon') : '' }}
                        </h2>
                        @if ($coupons > 0)
                            <h3 id="section-coupon">Utilizza coupon</h3>
                            <div class="btn btn-success" id="useCoupon">Seleziona buono sconto</div>
                            <div id="customer-coupons" class="d-none">
                                <select id="coupon-select" class="form-select" name="coupon" autocomplete="coupon"
                                    type="text">
                                </select>
                                <div id="selected-discount"></div>
                            </div>
                        @endif
                    </div>
                @endif


            </div>
        </form>
    </div>

    {{-- SCRIPT --}}
    <script>
        // attributes
        const VALUE_COUPON = 5;
        let amount = document.getElementById('amount');
        let customerSelected = document.getElementById('customer-select');
        let sectionCoupon = document.getElementById('section-coupon');
        let coustomerBlockCoupon = document.getElementById('selected-discount');
        let coupons = document.getElementById('customer-coupons');
        let couponsAvailable = @json($coupons);


        // if exist coupon attribute or take value null
        const USE_COUPON = document.getElementById('useCoupon');

        // set initial amount with flag if no sette
        let initialAmount = 0;
        let initialAmountSet = false;


        // function at change select reedirect at route selected client
        document.getElementById('customer-select').addEventListener('change', function() {
            let customerId = this.value;
            if (customerId) {
                // id passato e sostituito quando la rotta è composta dopo aver usato il metodo replace per mettere la variabile js customerId, :id è un placeholder.
                let url = '{{ route('admin.purchases.create', ['id' => 'placeholderId']) }}';
                url = url.replace('placeholderId', customerId);

                window.location.href = url;
            }
        });

        // if USE_COUPON is setted add functions to use coupons
        // btn to see coupon
        if (USE_COUPON) {
            USE_COUPON.addEventListener('click', function() {
                // retes when click btn use coupon
                initialAmountSet = false;
                coustomerBlockCoupon.innerHTML = "";
                sectionCoupon.innerHTML = "";
                // set initial value if no setted
                if (!initialAmountSet) {
                    initialAmount = amount.value;
                    initialAmountSet = true;
                }

                if (amount.value >= VALUE_COUPON * 2) {
                    let selectCoupon = document.getElementById('coupon-select');
                    coupons.classList.toggle('d-none');
                    selectCoupon.innerHTML = "";
                    // if you select and toggle again reset the selection option
                    if (coupons.classList.contains('d-none')) {
                        document.getElementById('coupon-select').selectedIndex = 0;
                        // TODO: fix when toggle clean innerhtml values
                    }
                    amount.disabled = !amount.disabled;

                    selectCoupon.innerHTML += `<option value="">Quantità coupon utilizzabili</option>`
                    for (let i = 1; i <= getMaxCoupons(); i++) {
                        selectCoupon.innerHTML += `<option value="${i}">${i} Coupon</option>`
                    }
                } else {
                    sectionCoupon.innerHTML =
                        `<p class="text-danger"> È necessario inserire un importo di almeno<br> ${VALUE_COUPON * 2}€</p>`
                }

            });
            // click select coupon
            document.getElementById('coupon-select').addEventListener('change', function() {
                coustomerBlockCoupon.innerHTML = "";
                let selectedCoupon = this.value;
                coustomerBlockCoupon.innerHTML +=
                    `<div class="card">Lo sconto selezionato è di : ${ selectedCoupon * VALUE_COUPON }€</div>`;
                amount.value = initialAmount - (selectedCoupon * VALUE_COUPON);
            });

        }


        // custome btn reset
        document.getElementById('btn-reset').addEventListener('click', (event) => {
            event.preventDefault();
            amount.disabled = false;
            amount.value = "";
            coupons.classList.add('d-none');
            coustomerBlockCoupon.innerHTML = "";
        });

        // submit btn prevent and take off
        document.getElementById('btn-submit').addEventListener('click', (event) => {
            event.preventDefault();
            console.log(customerSelected);
            customerSelected.disabled = false;
            amount.disabled = false;
            document.getElementById('form-purchase').submit();
        });



        // function to calculate de max utilizable coupons
        function getMaxCoupons() {
            let price = parseFloat(amount.value);
            let discountMax = price / 2;


            // take max coupon for the max discount value
            let maxCoupons = Math.floor(discountMax / VALUE_COUPON);


            return Math.min(maxCoupons, couponsAvailable);
        }
    </script>


    {{-- \ SCRIPT --}}
@endsection
