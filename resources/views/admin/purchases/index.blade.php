<?php
use App\Functions\Helper;
?>

@extends('layouts.admin')
@section('content')
    @if (session('success'))
        <div class="alert alert-success mt-3" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @dump($purchases)
    {{-- TODO: suddividere acquisti per mese settimana o anno --}}
    {{-- <ul>
        @forelse ($purchases as $purchase)
            <li>ID:{{ $purchase->id }} DATA:{{ Helper::formatDate($purchase->created_at) }} IMPORTO:
                €{{ $purchase->amount }} <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-warning">
                    <i class="fa-solid fa-pencil "></i>
                </a>
                <form class="d-inline-block" action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST"
                    onsubmit=" return confirm('Sei sicuro di voler cancellare questo acquisto?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </form>
            </li>
        @empty
            <li>Nessun acquisto</li>
        @endforelse
    </ul> --}}
    <table class="table table-bordered border-success">
        <thead class="table-success">
            <tr>
                <th scope="col">
                    ID Acquisto
                </th>
                <th scope="col">
                    Nome cliente
                </th>
                <th scope="col">
                    Data Acquisto
                </th>
                <th scope="col">
                    Importo
                </th>

                <th scope="col">
                    Azioni
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->id }}</td>
                    <td>{{ $purchase->customer?->name }}</td>
                    <td>{{ Helper::formatDate($purchase->created_at) }}</td>
                    <td>€{{ $purchase->amount }}</td>

                    <td class="text-center">
                        <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-warning">
                            <i class="fa-solid fa-pencil "></i>
                        </a>
                        <form class="d-inline-block" action="{{ route('admin.purchases.destroy', $purchase) }}"
                            method="POST" onsubmit=" return confirm('Sei sicuro di voler cancellare questo acquisto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <li>Nessun acquisto</li>
            @endforelse
        </tbody>
    </table>


    <div class="paginator w-50">
        {{ $purchases->links() }}
    </div>
@endsection
