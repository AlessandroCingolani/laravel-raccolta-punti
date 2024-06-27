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
        @dump(old('name', $purchase?->customer))
        @dump(old('name', $purchase?->customer_id))

        @dump($customers_name)
        @dump($customer_selected)
        @dump($coupons)
        {{-- TODO: style --}}
        <div class="mb-3">
            <a href="{{ route('admin.customers.create') }}">Aggiungi nuovo cliente
            </a>
        </div>
        <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
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
                                    value="{{ $name->id }}">{{ $name->name }} </option>
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

                    <button type="submit" class="btn btn-primary">{{ $button }}</button>
                    <button type="reset" class="btn btn-secondary">Annulla</button>
                </div>
                {{-- TODO: fare uno script che quando selezioni un coupon ti fa vedere lo sconto.
                     controllare sempre quando cambia utente selezionato dovrei far in modo che si aggiorna la pagina con il nuovo cliente selezionato --}}
                <div class="col-4">
                    <h2>{{ $coupons > 0 ? (!is_null($customer_selected?->name) ? $customer_selected->name . ' ha ' . $coupons . ' coupons disponibili' : $purchase?->customer->name . ' ha ' . $coupons . ' coupons disponibili') : 'Il cliente non ha coupon da utilizzare' }}
                    </h2>
                    @if ($coupons > 0)
                        <h3>Utilizza coupon</h3>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- SCRIPT --}}
    <script>
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
    </script>


    {{-- \ SCRIPT --}}
@endsection
