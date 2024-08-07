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
        $customers = Customer::select('customers.*', DB::raw('COALESCE(SUM(purchases.amount), 0) as total_spent'))
            ->leftJoin('purchases', function ($join) {
                $join->on('purchases.customer_id', '=', 'customers.id')
                    ->whereBetween('purchases.created_at', Helper::getReferencePeriod());
            })
            ->groupBy('customers.id', 'customers.name', 'customers.surname', 'customers.email', 'customers.phone', 'customers.customer_points', 'customers.created_at', 'customers.updated_at')
            ->orderBy('total_spent', 'desc')
            ->paginate(10);

        $direction = 'desc';
        $coupons = 0;

        return view("admin.customers.index", compact('customers', 'direction'));
    }

    // Order Customer for col and direction
    public function orderBy($direction, $column)
    {
        $direction = ($direction == 'desc') ? 'asc' : 'desc';


        $customers = Customer::select('customers.*', DB::raw('COALESCE(SUM(purchases.amount), 0) as total_spent'))
            ->leftJoin('purchases', function ($join) {
                $join->on('purchases.customer_id', '=', 'customers.id')
                    ->whereBetween('purchases.created_at', Helper::getReferencePeriod());
            })
            ->groupBy('customers.id', 'customers.name', 'customers.surname', 'customers.email', 'customers.phone', 'customers.customer_points', 'customers.created_at', 'customers.updated_at')
            ->orderBy($column, $direction)
            ->paginate(10);


        return view('admin.customers.index', compact('customers', 'direction'));
    }

    // Customers research input header

    public function searchCustomer(Request $request)
    {

        $searchValue = $request->input('tosearch');

        $fullName = explode(' ', $searchValue);


        $direction = 'desc';
        $customers = Customer::select('customers.*', DB::raw('COALESCE(SUM(purchases.amount), 0) as total_spent'))
            ->leftJoin('purchases', function ($join) {
                $join->on('purchases.customer_id', '=', 'customers.id')
                    ->whereBetween('purchases.created_at', Helper::getReferencePeriod());
            })

            ->groupBy('customers.id', 'customers.name', 'customers.surname', 'customers.email', 'customers.phone', 'customers.customer_points', 'customers.created_at', 'customers.updated_at');
        if (count($fullName) === 2) {
            $firstName = $fullName[0];
            $lastName = $fullName[1];
            $customers->where('name', 'LIKE', '%' . $firstName . '%')
                ->orWhere('surname', 'LIKE', '%' . $lastName . '%');
        } else {
            $customers->where('name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('surname', 'LIKE', '%' . $searchValue . '%');
        };

        $customers = $customers->paginate(10)
            // appends fix the problem paginate concat tosearch at page
            ->appends(['tosearch' => $request['tosearch']]);
        return view('admin.customers.index', compact('customers', 'direction'));
    }

    // Filter customer
    public function filterCustomer(Request $request)
    {

        $purchaseRange = $request->input('purchaseRange', 0);
        $withCoupons = $request->input('coupons');

        //TODO: when false withCoupons take all customers if true take only customers with coupons
        $direction = 'desc';
        $customers = Customer::select('customers.*', DB::raw('COALESCE(SUM(purchases.amount), 0) as total_spent'))
            ->leftJoin('purchases', function ($join) {
                $join->on('purchases.customer_id', '=', 'customers.id')
                    ->whereBetween('purchases.created_at', Helper::getReferencePeriod());
            })

            ->groupBy('customers.id', 'customers.name', 'customers.surname', 'customers.email', 'customers.phone', 'customers.customer_points', 'customers.created_at', 'customers.updated_at')
            ->having(DB::raw('total_spent'), '>=', $purchaseRange)

            ->orderBy('total_spent', $direction);
        if ($withCoupons === "true") {
            // customers points 10 are 1 coupon
            $customers->having(DB::raw('customer_points'), '>=', 10);
        }
        $customers =  $customers->paginate(10)->appends([
            'purchaseRange' => $purchaseRange,
            'coupons' => $withCoupons
        ]);
        return view('admin.customers.index', compact('customers', 'direction', 'withCoupons', 'purchaseRange'));
    }

    // PRINT COUPON

    public function printCoupon(Request $request)
    {
        $form_data = $request->all();
        $customer = Customer::find($form_data['customer']);
        // check if is null coupon or send coupons major then actual customer points
        if (is_null($form_data['coupon']) || $customer->customer_points < Helper::couponToPoints($form_data['coupon'])) {
            return back()->with('fail', 'Errore stampa non riuscita!');
        }

        $customer->update(array('customer_points' => ($customer->customer_points - Helper::couponToPoints($form_data['coupon']))));
        return back()->with('success', 'Coupon Stampato con successo');
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
        // camel case name and from form
        $form_data['name'] = ucwords($form_data['name']);
        $form_data['surname'] = ucwords($form_data['surname']);
        $form_data['address'] = $form_data['address'] ? ucwords($form_data['address']) : null;
        $form_data['city'] = $form_data['city'] ? ucwords($form_data['city']) : null;

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

        $coupons_used = Purchase::where('customer_id', $customer->id)->whereBetween('created_at', Helper::getReferencePeriod())->sum('coupons_used');

        $coupons = $customer?->customer_points >= 10 ? Helper::discountCoupons($customer->customer_points) : 0;
        return view('admin.customers.show', compact('customer', 'purchases', 'amount', 'coupons', 'coupons_used'));
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
        $form_data['surname'] = ucwords($form_data['surname']);
        $form_data['address'] = $form_data['address'] ? ucwords($form_data['address']) : null;
        $form_data['city'] = $form_data['city'] ? ucwords($form_data['city']) : null;
        $customer->update($form_data);


        return redirect()->route('admin.customers.show', $customer)->with('success', 'Cliente modificato con successo!');
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
