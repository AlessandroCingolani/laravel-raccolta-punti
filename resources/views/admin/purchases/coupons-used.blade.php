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
        <h2 class="mb-3">
            {{ count($purchases) > 0 ? 'Acquisti con coupon ' : 'Nessun acquisto con coupon da visualizzare' }}</h2>
        @if (count($purchases) > 0)
            <table class="table table-bordered border-info">
                <thead class="table-info">
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
                            Importo totale
                        </th>
                        <th scope="col">
                            Importo scontato
                        </th>
                        <th scope="col">
                            Coupons utilizzati
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
                            <td>€{{ Helper::oldPriceWithoutCoupon($purchase->amount, $purchase->coupons_used) }}</td>
                            <td>€{{ $purchase->amount }}</td>
                            <td>{{ $purchase->coupons_used }}</td>

                        </tr>
                    @empty
                        <li>Nessun acquisto</li>
                    @endforelse
                </tbody>
            </table>


            <div class="paginator w-50">
                {{ $purchases->links() }}
            </div>
        @endif

    </div>
@endsection
