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



    // function custome
    public function clientPurchase($id)
    {
        $customer_selected = Customer::find($id);
        $title = "Aggiungi acquisto";
        $method = "POST";
        $route = route("admin.purchases.store");
        $purchase = null;
        $customers_name = Customer::orderBy('name')->get();
        $button = 'Aggiungi nuovo acquisto';
        return view('admin.purchases.create-edit', compact("title", "method", "purchase", "route", "button", "customers_name", "customer_selected"));
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
        $customer_selected = null;
        $customers_name = Customer::orderBy('name')->get();
        $button = 'Aggiungi nuovo acquisto';
        return view('admin.purchases.create-edit', compact("title", "method", "purchase", "route", "button", "customers_name", "customer_selected"));
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
        dump($points_earned);

        // create purchase
        $form_data = [
            'customer_id' => $customer_id,
            'amount' => $request['amount'],
            'points_earned' => $points_earned,
        ];
        $new_purchase = Purchase::create($form_data);
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
        $points_earned = Helper::generatePoints($request['amount']);
        $form_data = [
            'customer_id' => $customer_id,
            'amount' => $request['amount'],
            'points_earned' => $points_earned,
        ];
        $purchase->update($form_data);
        return redirect()->route('admin.purchases.index')->with('success', 'Acquisto modificato con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('admin.purchases.index')->with('success', 'Acquisto eliminato con successo');
    }
}
