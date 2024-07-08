<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Functions\Helper;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // default order by expenses customers amount
        $customers = Customer::select('customers.*', DB::raw('SUM(purchases.amount) as total_spent'))
            ->leftJoin('purchases', 'purchases.customer_id', '=', 'customers.id')
            ->whereBetween('purchases.created_at', Helper::getReferencePeriod())
            ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.phone', 'customers.customer_points', 'customers.created_at', 'customers.updated_at')
            ->orderBy('total_spent', 'desc')
            ->paginate(10);
        $direction = 'desc';
        $coupons = 0;
        return view("admin.customers.index", compact('customers', 'direction'));
    }

    // Order Customer for col and direction
    public function orderBy($direction, $column)
    {
        $direction = $direction == 'desc' ? 'asc' : 'desc';
        $customers = Customer::select('customers.*', DB::raw('SUM(purchases.amount) as total_spent'))
            ->leftJoin('purchases', 'purchases.customer_id', '=', 'customers.id')
            ->whereBetween('purchases.created_at', Helper::getReferencePeriod())
            ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.phone', 'customers.customer_points', 'customers.created_at', 'customers.updated_at')
            ->orderBy($column, $direction)
            ->paginate(10);
        return view('admin.customers.index', compact('customers', 'direction'));
    }

    // Customers research input header

    public function searchCustomer(Request $request)
    {
        $direction = 'desc';
        $customers = Customer::select('customers.*', DB::raw('SUM(purchases.amount) as total_spent'))
            ->leftJoin('purchases', 'purchases.customer_id', '=', 'customers.id')
            ->whereBetween('purchases.created_at', Helper::getReferencePeriod())
            ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.phone', 'customers.customer_points', 'customers.created_at', 'customers.updated_at')
            ->where('name', 'LIKE', '%' . $request['tosearch'] . '%')
            ->paginate(50); // TODO: need fix when search the link paginator refresh the results and broke the research
        return view('admin.customers.index', compact('customers', 'direction'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Aggiungi cliente";
        $method = 'POST';
        $route = route('admin.customers.store');
        $customer = null;
        $button = 'Crea nuovo cliente';

        return view('admin.customers.create-edit', compact('title', 'method', 'route', 'button', 'customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $form_data = $request->all();
        // camel case name from form
        $form_data['name'] = ucwords($form_data['name']);
        $new_customer = Customer::create($form_data);

        return redirect()->route('admin.customers.show', $new_customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {

        $amount = Purchase::where('customer_id', $customer->id)->whereBetween('created_at', Helper::getReferencePeriod())->sum('amount');
        $purchases = Purchase::where('customer_id', $customer->id)->orderBy('id', 'desc')->whereBetween('created_at', Helper::getReferencePeriod())->take(3)->get();
        return view('admin.customers.show', compact('customer', 'purchases', 'amount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $title = "Modifica cliente";
        $method = 'PUT';
        $route = route('admin.customers.update', $customer);
        $button = 'Modifica cliente';

        return view('admin.customers.create-edit', compact('title', 'method', 'route', 'button', 'customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $form_data = $request->all();
        // camel case name from form
        $form_data['name'] = ucwords($form_data['name']);
        $customer->update($form_data);


        return redirect()->route('admin.customers.show', $customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Cliente eliminato con successo');
    }
}
