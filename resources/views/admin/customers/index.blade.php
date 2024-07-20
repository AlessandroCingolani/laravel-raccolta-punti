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
        @if (count($customers) > 0)
            <h2 class="mb-3">Lista clienti</h2>
            <table class="table table-bordered border-info">
                <thead class="table-info">
                    <tr>
                        <th class="d-none d-lg-table-cell" scope="col">
                            <a class="text-decoration-none text-black"
                                href="{{ route('admin.order-by', ['direction' => $direction, 'column' => 'id']) }}">ID
                                <i class="fa-solid fa-sort"></i></a>
                        </th>
                        <th scope="col">
                            <a class="text-decoration-none text-black"
                                href="{{ route('admin.order-by', ['direction' => $direction, 'column' => 'name']) }}">Cliente
                                <i class="fa-solid fa-sort"></i></a>
                        </th>
                        <th scope="col">
                            Email
                        </th>
                        <th class="d-none d-lg-table-cell" scope="col">
                            Telefono
                        </th>
                        <th class="d-none d-lg-table-cell" scope="col">
                            <a class="text-decoration-none text-black"
                                href="{{ route('admin.order-by', ['direction' => $direction, 'column' => 'customer_points']) }}">Punti
                                Disponibili <i class="fa-solid fa-sort"></i>
                            </a>
                        </th>
                        <th class="d-none d-lg-table-cell" scope="col">
                            Coupons
                        </th>
                        <th scope="col">
                            <a class="text-decoration-none text-black"
                                href="{{ route('admin.order-by', ['direction' => $direction, 'column' => 'total_spent']) }}">Totale
                                Spesa <i class="fa-solid fa-sort"></i>
                            </a>
                        </th>
                        <th scope="col">
                            Azioni
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td class="d-none d-lg-table-cell">{{ $customer->id }}</td>
                            <td><a
                                    href="{{ route('admin.customers.show', $customer) }}">{{ $customer->name . ' ' . $customer->surname }}</a>
                            </td>
                            <td>{{ $customer?->email ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell">{{ $customer?->phone ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell">Punti :{{ $customer?->customer_points ?? '-' }}
                            </td>
                            <td class="d-none d-lg-table-cell">
                                {{ $customer->customer_points >= 10 ? 'Coupon disponibili: ' . Helper::discountCoupons($customer->customer_points) : 'Nessun coupon disponibile' }}
                            </td>
                            <td>{{ $customer?->total_spent ?? '0' }} €</td>
                            <td class="text-center">
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                                    <i class="fa-solid fa-pencil "></i>
                                </a>
                                <form class="d-inline-block" action="{{ route('admin.customers.destroy', $customer) }}"
                                    method="POST"
                                    onsubmit=" return confirm('Sei sicuro di voler cancellare questo cliente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                                {{-- Send email form with hidden values --}}

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h2>Nessun cliente trovato</h2>
        @endif

        <div class="paginator w-50">
            {{ $customers->links() }}
        </div>
    </div>
@endsection
