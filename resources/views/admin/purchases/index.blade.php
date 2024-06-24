@extends('layouts.admin')
@section('content')
    @if (session('success'))
        <div class="alert alert-success mt-3" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @dump($purchases)
    {{-- TODO: suddividere acquisti per mese settimana o anno --}}
    <ul>
        @forelse ($purchases as $purchase)
            <li>ID:{{ $purchase->id }} DATA:{{ $purchase->created_at }} IMPORTO: €{{ $purchase->amount }}</li>
        @empty
            <li>Nessun acquisto</li>
        @endforelse
    </ul>
    <div class="paginator w-50">
        {{ $purchases->links() }}
    </div>
@endsection
