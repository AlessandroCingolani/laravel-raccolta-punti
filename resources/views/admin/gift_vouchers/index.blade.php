@extends('layouts.admin')

@section('content')
    <div class="container-fluid p-3">
        @if (session('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif
        @if (count($vouchers) > 0)
            <div class="d-flex justify-content-between">
                <h2 class="mb-3">Lista buoni regalo</h2>
            </div>
            @foreach ($vouchers as $voucher)
                <p>{{ $voucher }}</p>
            @endforeach
        @else
            <h2>Nessun buono regalo disponibile</h2>
        @endif

        <div class="paginator w-50">
            {{ $vouchers->links() }}
        </div>
    </div>
@endsection
