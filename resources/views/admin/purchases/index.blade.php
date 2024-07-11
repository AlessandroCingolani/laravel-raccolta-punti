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
    <div class="container-fluid p-3">
        <h2 class="mb-3">{{ count($purchases) > 0 ? 'Tutti gli aquisti ' : 'Nessun acquisto da visualizzare' }}</h2>
        <table class="table table-bordered border-success">
            <thead class="table-success">
                <tr>
                    <th class="d-none d-lg-table-cell" scope="col">
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
                        <td class="d-none d-lg-table-cell">{{ $purchase->id }}</td>
                        <td><a
                                href="{{ route('admin.customers.show', $purchase->customer) }}">{{ $purchase->customer?->name . ' ' . $purchase->customer?->surname }}</a>
                        </td>
                        <td>{{ Helper::formatDate($purchase->created_at) }}</td>
                        <td>€{{ $purchase->amount }}</td>

                        <td class="text-center">
                            <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-warning">
                                <i class="fa-solid fa-pencil "></i>
                            </a>
                            <form class="d-inline-block" action="{{ route('admin.purchases.destroy', $purchase) }}"
                                method="POST"
                                onsubmit=" return confirm('Sei sicuro di voler cancellare questo acquisto?')">
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
    </div>
@endsection
