<?php
use App\Functions\Helper;
?>
@extends('layouts.admin')
@section('content')
    <div class="container-fluid p-5">
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
            <h2>Lista clienti</h2>
            <table class="table table-bordered border-success">
                <thead class="table-success">
                    <tr>
                        <th scope="col">
                            <a class="text-decoration-none text-black"
                                href="{{ route('admin.order-by', ['direction' => $direction, 'column' => 'id']) }}">ID
                                <i class="fa-solid fa-sort"></i></a>
                        </th>
                        <th scope="col">
                            <a class="text-decoration-none text-black"
                                href="{{ route('admin.order-by', ['direction' => $direction, 'column' => 'name']) }}">Nome
                                cliente <i class="fa-solid fa-sort"></i></a>
                        </th>
                        <th scope="col">
                            Email
                        </th>
                        <th scope="col">
                            Telefono
                        </th>
                        <th scope="col">
                            <a class="text-decoration-none text-black"
                                href="{{ route('admin.order-by', ['direction' => $direction, 'column' => 'customer_points']) }}">Punti
                                Disponibili <i class="fa-solid fa-sort"></i>
                            </a>
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
                            <td>{{ $customer->id }}</td>
                            <td><a href="{{ route('admin.customers.show', $customer) }}">{{ $customer->name }}</a></td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer?->phone ?? '-' }}</td>
                            <td>Punti :{{ $customer?->customer_points ?? '-' }} -
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
                                <form class="d-inline-block" id="emailForm" action="{{ route('admin.send-email') }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" id="name" name="name" value="{{ $customer->name }}">
                                    <input type="hidden" id="email" name="email" value="{{ $customer->email }}">
                                    <input type="hidden" id="customer_points" name="customer_points"
                                        value="{{ $customer->customer_points }}">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="fa-solid fa-envelope"></i></button>
                                </form>
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
