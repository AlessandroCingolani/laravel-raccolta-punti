<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;
use App\Functions\Helper;


class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::orderBy('id', 'desc')->paginate(20);
        return view('admin.purchases.index', compact('purchases'));
    }



    // function custome route from show customer at create new purchase
    public function clientPurchase($id)
    {
        $customer_selected = Customer::find($id);
        $coupons = 0;
        if ($customer_selected->customer_points >= 10) {
            $coupons = Helper::discountCoupons($customer_selected->customer_points);
        }
        $title = "Aggiungi acquisto";
        $method = "POST";
        $route = route("admin.purchases.store");
        $purchase = null;
        $customers_name = Customer::orderBy('name')->get();
        $button = 'Aggiungi nuovo acquisto';
        return view('admin.purchases.create-edit', compact("title", "method", "purchase", "route", "button", "customers_name", "customer_selected", "coupons"));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Aggiungi acquisto";
        $method = "POST";
        $route = route("admin.purchases.store");
        $purchase = null;
        $coupons = 0;
        $customer_selected = null;
        $customers_name = Customer::orderBy('name')->get();
        $button = 'Aggiungi nuovo acquisto';
        return view('admin.purchases.create-edit', compact("title", "method", "purchase", "route", "button", "customers_name", "customer_selected", "coupons"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseRequest $request)
    {
        $form_data = $request->all();

        // take id customer
        $customer_id = $request['id'];


        // take point to add at purchase
        $points_earned = Helper::generatePoints($request['amount']);


        // create purchase
        $form_data = [
            'customer_id' => $customer_id,
            'amount' => $request['amount'],
            'points_earned' => $points_earned,
        ];
        // take customer from id
        $customer = Customer::find($customer_id);
        $new_purchase = Purchase::create($form_data);

        // update column customer points if point earned > 0
        if ($points_earned > 0) {
            $customer->update(array('customer_points' => ($customer->customer_points + $points_earned)));
        }
        return redirect()->route('admin.customers.show', $customer_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $title = "Modifica acquisto";
        $method = "PUT";
        $route = route("admin.purchases.update", $purchase);
        $customer_selected = null;
        $customers_name = Customer::orderBy('name')->get();
        $button = 'Modifica acquisto';
        return view('admin.purchases.create-edit', compact("title", "method", "purchase", "route", "button", "customers_name", "customer_selected"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseRequest $request, Purchase $purchase)
    {

        $form_data = $request->all();
        $customer_id = $request['id'];
        $new_points_earned = Helper::generatePoints($request['amount']);
        $form_data = [
            'customer_id' => $customer_id,
            'amount' => $request['amount'],
            'points_earned' => $new_points_earned,
        ];
        // take customer from id
        $customer = Customer::find($customer_id);
        // update column customer points if old purchase amount in < of new request
        if ($purchase->amount < $request['amount']) {
            $customer->update(array('customer_points' => ($customer->customer_points + ($new_points_earned - $purchase->points_earned))));
        } elseif ($purchase->amount > $request['amount']) {
            $customer->update(array('customer_points' => ($customer->customer_points - ($purchase->points_earned - $new_points_earned))));
        }
        $purchase->update($form_data);
        return redirect()->route('admin.purchases.index')->with('success', 'Acquisto modificato con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $customer = Customer::find($purchase->customer_id);
        $customer->update(array('customer_points' => ($customer->customer_points - $purchase->points_earned)));
        $purchase->delete();
        return redirect()->route('admin.purchases.index')->with('success', 'Acquisto eliminato con successo');
    }
}
