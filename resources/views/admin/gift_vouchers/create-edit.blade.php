@extends('layouts.admin')

@section('content')

    <div class="container-fluid">

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif
        <h2 class="my-3">{{ $title }}</h2>
        <div class="row">
            <div class="col-md-8">
                <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method($method)
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="recipient_first_name" class="form-label">Nome destinatario *</label>
                            <input id="recipient_first_name"
                                class="form-control @error('recipient_first_name') is-invalid @enderror"
                                name="recipient_first_name"
                                value="{{ old('recipient_first_name', $gift_voucher?->recipient_first_name) }}"
                                autocomplete="recipient_first_name" type="text">
                            @error('recipient_first_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="recipient_last_name" class="form-label">Cognome destinatario *</label>
                            <input id="recipient_last_name"
                                class="form-control @error('recipient_last_name') is-invalid @enderror"
                                name="recipient_last_name"
                                value="{{ old('recipient_last_name', $gift_voucher?->recipient_last_name) }}"
                                autocomplete="recipient_last_name" type="text">
                            @error('recipient_last_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Importo buono *</label>
                            <div id="operation-discount" class="d-none">

                            </div>
                            <input id="amount" class="form-control @error('amount') is-invalid @enderror" name="amount"
                                value="{{ old('amount', $gift_voucher?->amount) }}" step=0.01 placeholder="importo €"
                                autocomplete="amount" type="number">

                            @error('amount')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary">{{ $button }}</button>
                    <button type="reset" class="btn btn-secondary">Annulla</button>
                </form>
            </div>
        </div>
    </div>
@endsection
