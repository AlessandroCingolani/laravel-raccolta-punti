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

                    <div class="col-md-6">
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
                    @if (count($purchases) > 0)
                        <div class="col-md-6">
                            <h3>{{ count($purchases) === 1 ? 'Ultimo acquisto:' : 'Ultimi ' . count($purchases) . ' aquisti:' }}
                            </h3>
                            <ul>
                                @foreach ($purchases as $purchase)
                                    <li class="mb-2">ID: {{ $purchase->id }}
                                        Data: {{ Helper::formatDate($purchase->created_at) }}
                                        Importo: €{{ $purchase->amount }}
                                        <a href="{{ route('admin.purchases.edit', $purchase) }}" class="btn btn-warning">
                                            <i class="fa-solid fa-pencil "></i>
                                        </a>
                                        <form class="d-inline-block"
                                            action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST"
                                            onsubmit=" return confirm('Sei sicuro di voler cancellare questo acquisto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h3>Informazioni Aggiuntive</h3>
                        <p><strong>Città:</strong> {{ $customer->city ?? 'Non disponibile' }}</p>
                        <p><strong>Indirizzo:</strong> {{ $customer->address ?? 'Non disponibile' }}</p>
                        <p><strong>Data di Registrazione:</strong> {{ Helper::formatDate($customer->created_at) }}</p>

                    </div>
                    <div class="col-md-6">
                        <h4>Stampa coupon cartaceo</h4>
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

    </div>
@endsection
