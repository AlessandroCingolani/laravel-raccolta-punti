@extends('layouts.admin')
@section('content')
    <h2>CLIENTE {{ $customer->name }}</h2>
    <div class="mb-3">
        <a href="{{ route('admin.selected-client', $customer) }}">Aggiungi nuovo acquisto
        </a>
    </div>
    <ul>
        @forelse ($purchases as $purchase)
            <li>ID:{{ $purchase->id }} Amount: €{{ $purchase->amount }}</li>
        @empty
            <li>Nessun acquisto</li>
        @endforelse
    </ul>
@endsection
