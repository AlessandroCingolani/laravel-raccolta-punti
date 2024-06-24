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
            <div class="col-4">
                <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method($method)

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Cliente *</label>
                        <input id="name" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name', $customer?->name) }}" autocomplete="name" type="text">
                        @error('name')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Cliente *</label>
                        <input id="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email', $customer?->email) }}" autocomplete="email" type="text">

                        @error('email')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefono Cliente </label>
                        <input id="phone" class="form-control" name= "phone"
                            value="{{ old('phone', $customer?->phone) }}" autocomplete="phone" type="text">

                    </div>


                    <button type="submit" class="btn btn-primary">{{ $button }}</button>
                    <button type="reset" class="btn btn-secondary">Annulla</button>
                </form>
            </div>
        </div>
    </div>
@endsection
