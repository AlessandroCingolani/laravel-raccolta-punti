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
        <div class="row">
            <div class="col-4">
                <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method($method)

                    <div class="mb-3">
                        <label for="name" class="control-label">Nome Cliente *</label>
                        <select id="name" class="form-select @error('name') is-invalid @enderror" name="name"
                            autocomplete="name" type="text">
                            <option value="">Seleziona cliente</option>
                            @forelse ($customers_name as $name)
                                <option {{ old('name', $purchase?->customer_id) === $name->id ? 'selected' : '' }}
                                    value="{{ $name->id }}">{{ $name->name }} </option>
                            @empty
                                <option value="">Nessun cliente</option>
                            @endforelse

                        </select>
                        @error('name')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Pagamento Cliente *</label>
                        <input id="amount" class="form-control @error('amount') is-invalid @enderror" name="amount"
                            value="{{ old('ampunt', $purchase?->amount) }}" step=0.01 placeholder="€" autocomplete="amount"
                            type="number">

                        @error('amount')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">{{ $button }}</button>
                    <button type="reset" class="btn btn-secondary">Annulla</button>
                </form>
            </div>
        </div>
    </div>
@endsection
