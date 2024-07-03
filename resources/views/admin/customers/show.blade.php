<?php
use App\Functions\Helper;
?>


@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h2>Dettagli Cliente</h2>
            </div>
            <div class="card-body">
                <div class="row mb-3">

                    <div class="col-md-8">
                        <h4>Cliente: <span class="fs-3">{{ $customer->name }}</span></h4>
                        <p><strong>Email:</strong> {{ $customer?->email ?? 'Non disponibile' }}</p>
                        <p><strong>Telefono:</strong> {{ $customer->phone ?? 'Non disponibile' }}</p>
                        <p><strong>Punti Totali:</strong> <span
                                class="badge bg-secondary ms-2 fs-5">{{ $customer->customer_points }}</span></p>
                        <p><strong>Coupons:</strong><span
                                class="badge bg-secondary ms-2 fs-5">{{ Helper::discountCoupons($customer->customer_points) }}</span>
                        </p>
                        <p><strong>Importo totale speso:</strong><span class="badge bg-primary ms-2 fs-5">{{ $amount }}
                                €</span>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h3>Informazioni Aggiuntive</h3>
                        <p><strong>Città:</strong> {{ $customer->city ?? 'Non disponibile' }}</p>
                        <p><strong>Indirizzo:</strong> {{ $customer->address ?? 'Non disponibile' }}</p>
                        <p><strong>Data di Registrazione:</strong> {{ Helper::formatDate($customer->created_at) }}</p>

                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.purchases.create', ['id' => $customer]) }}" class="btn btn-success">Nuovo
                            acquisto
                        </a>
                    </div>
                    <div class="col-md-4  mb-3">
                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-primary">Modifica</a>
                    </div>
                    <div class="col-md-4  mb-3 text-right">
                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Sei sicuro di voler eliminare questo cliente?')">Elimina</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{--
        <ul>
            @forelse ($purchases as $purchase)
                <li>ID:{{ $purchase->id }} Amount: €{{ $purchase->amount }}</li>
            @empty
                <li>Nessun acquisto</li>
            @endforelse
        </ul> --}}
    </div>
@endsection
