@extends('layouts.admin')
@section('content')
    <h2>CLIENTE {{ $customer->name }}</h2>

    <ul>
        @forelse ($purchases as $purchase)
            <li>ID:{{ $purchase->id }} Amount: €{{ $purchase->amount }}</li>
        @empty
            <li>Nessun acquisto</li>
        @endforelse
    </ul>
@endsection
