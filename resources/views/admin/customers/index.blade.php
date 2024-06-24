@extends('layouts.admin')
@section('content')
    <h2>Customers</h2>
    @dump($customers)
    <div class="container p-2">
        <table class="table table-bordered border-success">
            <thead class="table-success">
                <tr>
                    <th scope="col">
                        ID
                    </th>
                    <th scope="col">
                        <a class="text-decoration-none text-black"
                            href="{{ route('admin.order-by', ['direction' => $direction, 'column' => 'name']) }}">Nome
                            cliente</a>
                    </th>
                    <th scope="col">
                        Email
                    </th>
                    <th scope="col">
                        Telefono
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
                        <td class="text-center">
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                                <i class="fa-solid fa-pencil "></i>
                            </a>
                            <form class="d-inline-block" action="{{ route('admin.customers.destroy', $customer) }}"
                                method="POST" onsubmit=" return confirm('Sei sicuro di voler cancellare questo cliente?')">
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
        <div class="paginator w-50">
            {{ $customers->links() }}
        </div>
    </div>
@endsection
