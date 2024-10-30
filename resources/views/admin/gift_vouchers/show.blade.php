<?php
use App\Functions\Helper;
?>
@extends('layouts.admin')

@section('content')
    <div class="row w-100 justify-content-center">
        <div class="col-lg-6 col-10">
            <h2 class="text-center mt-3">Buono regalo </h2>
            <div class="card w-100 shadow-sm my-4 p-4">
                <div class="card-body position-relative">
                    {{-- Stato del voucher in alto a destra --}}
                    <span
                        class="position-absolute top-0 end-0 badge
                        {{ $gift_voucher->status === 'expired' ? 'bg-danger' : ($gift_voucher->status === 'used' ? 'bg-primary' : 'bg-success') }}">
                        {{ $gift_voucher->status === 'expired' ? 'Scaduto' : ($gift_voucher->status === 'used' ? 'Usato' : 'Valido') }}
                    </span>


                    {{-- Nome del destinatario centrato --}}
                    <h2 class="text-center my-3">{{ $gift_voucher->recipient_first_name }}
                        {{ $gift_voucher->recipient_last_name }}
                    </h2>

                    {{-- TODO: ridimensionamento schermata si sovrappongono le scritte in absolute --}}

                    {{-- Importo del voucher sotto il nome --}}
                    <p class="text-center fs-4 text-primary">{{ number_format($gift_voucher->amount, 2) }} €</p>

                    {{-- Data di creazione in basso a sinistra --}}
                    <div class="text-muted small position-absolute bottom-0 start-0">
                        Acquistato il {{ Helper::formatDate($gift_voucher->created_at) }}
                    </div>


                    <div class="text-muted small position-absolute bottom-0 end-0">
                        {{ $gift_voucher->status === 'expired'
                            ? 'Scaduto il ' . Helper::formatDate($gift_voucher->created_at->addMonths($monthsToAdd))
                            : ($gift_voucher->status === 'valid'
                                ? 'Valido fino a ' . Helper::formatDate($gift_voucher->created_at->addMonths($monthsToAdd))
                                : 'Usato il ' . Helper::formatDate($gift_voucher->used_at)) }}
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
