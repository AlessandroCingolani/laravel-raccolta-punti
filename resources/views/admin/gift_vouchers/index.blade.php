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

        @if (count($vouchers) > 0)
            <div class="d-flex justify-content-between">
                <h2 class="mb-3">Lista buoni regalo</h2>
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
                            Codice Voucher
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
                            <td>{{ $voucher->recipient_first_name . ' ' . $voucher->recipient_last_name }}</td>
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
                                <a href="{{ route('admin.gift_vouchers.edit', $voucher) }}" class="btn btn-warning">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <form class="d-inline-block" action="{{ route('admin.gift_vouchers.destroy', $voucher) }}"
                                    method="POST"
                                    onsubmit="return confirm('Sei sicuro di voler cancellare questo buono?')">
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
