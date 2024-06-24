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
            <li>ID:{{ $purchase->id }} DATA:{{ $purchase->created_at }} IMPORTO: €{{ $purchase->amount }} <a
                    href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-warning">
                    <i class="fa-solid fa-pencil "></i>
                </a></li>
        @empty
            <li>Nessun acquisto</li>
        @endforelse
    </ul>
    <div class="paginator w-50">
        {{ $purchases->links() }}
    </div>
@endsection
