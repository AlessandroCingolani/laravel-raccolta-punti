<?php
use App\Functions\Helper;
?>

@extends('layouts.admin')


@section('content')
    <div class="container-fluid p-3">
        @if (session('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="position-absolute end-0">
            {{-- TODO: Imposta grafica per bottoni rotta gift usati e scaduti --}}
            <a href="{{ Route::is('admin.gift-used') ? route('admin.gift_vouchers.index') : route('admin.gift-used') }}"
                class="btn {{ Route::is('admin.gift-used') ? 'btn-primary' : 'btn-warning' }}">
                {{ Route::is('admin.gift-used') ? 'Buoni regalo Validi' : 'Buoni regalo Usati' }}
            </a>
            <a href="{{ Route::is('admin.gift-expired') ? route('admin.gift_vouchers.index') : route('admin.gift-expired') }}"
                class="btn {{ Route::is('admin.gift-expired') ? 'btn-primary' : 'btn-danger' }}">
                {{ Route::is('admin.gift-expired') ? 'Buoni regalo Validi' : 'Buoni regalo Scaduti' }}
            </a>
        </div>


        @if (count($vouchers) > 0)
            <h2 class="mb-3">{{ $title }}</h2>
            <div class="d-none d-md-block col-md-4 position-relative mb-3">
                {{-- componente autocomplete  --}}
                @include('admin.partials.autocomplete', [
                    'idInput' => 'searchGift',
                    'idResults' => 'resultsGift',
                    'idError' => 'errorGift',
                    'route' => 'admin.search-gift-customers',
                    'label' => 'Ricerca buoni regalo',
                    'api' => 'http://127.0.0.1:8000/api/auto-complete-gift/',
                    'email' => false,
                    'code' => true,
                ])
            </div>
            <table class="table table-bordered border-info">
                <thead class="table-info">
                    <tr>
                        <th scope="col">
                            ID
                        </th>
                        <th scope="col">
                            Destinatario
                        </th>
                        <th scope="col">
                            Codice Regalo
                        </th>
                        <th scope="col">
                            Importo
                        </th>
                        <th scope="col">
                            Data di Scadenza
                        </th>
                        <th scope="col">
                            Stato
                        </th>
                        <th scope="col">
                            Azioni
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vouchers as $voucher)
                        <tr>
                            <td>{{ $voucher->id }}</td>
                            <td class="name-table"><a
                                    href="{{ route('admin.gift_vouchers.show', $voucher) }}">{{ $voucher->recipient_first_name . ' ' . $voucher->recipient_last_name }}</a>
                            </td>
                            <td>{{ $voucher->code }}</td>
                            <td>{{ $voucher->amount }} €</td>
                            <td>{{ Helper::formatDate($voucher->expiration_date) }}</td>
                            <td>
                                @if ($voucher->used_at)
                                    Usato il {{ $voucher->used_at }}
                                @else
                                    {{ $voucher->status }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($voucher->status === 'valid' || $voucher->status === 'used')
                                    <form action="{{ route('admin.gift_vouchers.toggleStatus', $voucher) }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('{{ $voucher->status === 'valid' ? 'Sei sicura/o di usare questo buono?' : 'Sei sicura/o di rendere valido questo buono?' }}')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success">
                                            @if ($voucher->status === 'valid')
                                                <i class="fa-solid fa-circle-dollar-to-slot"></i>
                                            @else
                                                <i class="fa-solid fa-check-circle"></i>
                                            @endif
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.gift_vouchers.edit', $voucher) }}" class="btn btn-warning">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <form class="d-inline-block" action="{{ route('admin.gift_vouchers.destroy', $voucher) }}"
                                    method="POST"
                                    onsubmit="return confirm('Sei sicura/o di voler cancellare questo buono?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h2>Nessun buono regalo disponibile</h2>
        @endif

        <div class="paginator w-50">
            {{ $vouchers->links() }}
        </div>
    </div>
@endsection
