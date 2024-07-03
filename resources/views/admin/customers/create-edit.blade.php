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
        <div class="row">
            <div class="col-md-8 col-lg-4">
                <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method($method)

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome e Cognome *</label>
                        <input id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name', $customer?->name) }}" autocomplete="name" type="text">
                        @error('name')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input id="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email', $customer?->email) }}" autocomplete="email" type="text">

                        @error('email')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefono </label>
                        <input id="phone" class="form-control @error('phone') is-invalid @enderror" name= "phone"
                            value="{{ old('phone', $customer?->phone) }}" autocomplete="phone" type="text">
                        @error('phone')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Indirizzo </label>
                        <input id="address" class="form-control @error('address') is-invalid @enderror" name= "address"
                            value="{{ old('address', $customer?->address) }}" autocomplete="address" type="text">
                        @error('address')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">Città </label>
                        <input id="city" class="form-control @error('city') is-invalid @enderror" name= "city"
                            value="{{ old('city', $customer?->city) }}" autocomplete="city" type="text">
                        @error('city')
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
