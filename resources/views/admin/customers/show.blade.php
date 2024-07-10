<?php
use App\Functions\Helper;
?>


@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        {{-- print success --}}
        @if (session('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @endif
        {{-- session fail error  --}}
        @if (session('fail'))
            <div class="alert alert-danger mt-3" role="alert">
                {{ session('fail') }}
            </div>
        @endif
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
                        <p><strong>Importo totale speso:</strong><span
                                class="badge bg-primary ms-2 fs-5">{{ $amount }}
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
                        <p><strong>Invia email:</strong>
                            {{-- Send email form with hidden values --}}
                        <form class="d-inline-block" id="emailForm" action="{{ route('admin.send-email') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" id="recipient" name="recipient" value="{{ $customer->name }}">
                            <input type="hidden" id="email" name="email" value="{{ $customer->email }}">
                            <input type="hidden" id="type" name="type" value="coupon">
                            <input type="hidden" id="customer_points" name="customer_points"
                                value="{{ $customer->customer_points }}">
                            <button type="submit" class="btn btn-primary"><i
                                    class="fa-solid fa-envelope me-2"></i>Coupons</button>
                        </form>
                        </p>


                    </div>
                    @if ($customer->customer_points >= 10)
                        <div class="col-md-2">
                            <h4>Stampa coupon cartaceo</h4>
                            <form id="form-coupon-print" action="{{ route('admin.print-coupon') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <select id="coupon-select" class="form-select" name="coupon" autocomplete="coupon"
                                    type="text">
                                </select>
                                <div class="my-3" id="selected-discount"></div>
                                {{-- pass hidden input id customer --}}
                                <input type="hidden" id="customer" name="customer" value="{{ $customer->id }}">
                                <button id="submit-print" type="submit" class="btn btn-success mt-2">Stampa
                                    Coupon
                                </button>
                            </form>

                        </div>
                    @endif
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.purchases.create', ['id' => $customer]) }}" class="btn btn-success">Nuovo
                            acquisto
                        </a>
                    </div>
                    <div class="col-md-4  mb-3">
                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-primary">Modifica
                            cliente</a>
                    </div>
                    <div class="col-md-4  mb-3 text-right">
                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Sei sicuro di voler eliminare questo cliente?')">Elimina
                                cliente</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        const VALUE_COUPON = 5;
        let selectCoupon = document.getElementById('coupon-select');
        let couponsAvailable = @json($coupons);
        let coustomerBlockCoupon = document.getElementById('selected-discount');


        selectCoupon.innerHTML += `<option value="">Quantità coupon utilizzabili</option>`
        for (let i = 1; i <= couponsAvailable; i++) {
            selectCoupon.innerHTML += `<option value="${i}">${i} Coupon</option>`
        }


        document.getElementById('coupon-select').addEventListener('change', function() {
            coustomerBlockCoupon.innerHTML = "";
            let selectedCoupon = this.value;
            coustomerBlockCoupon.innerHTML +=
                `<div class="card">Lo sconto selezionato è di : ${ selectedCoupon * VALUE_COUPON }€</div>`;
        });

        // submit btn prevent
        document.getElementById('submit-print').addEventListener('click', (event) => {
            event.preventDefault();
            document.getElementById('form-coupon-print').submit();
        });
    </script>
@endsection
