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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Aggiungi acquisto";
        $method = "POST";
        $route = route("admin.purchases.store");
        $purchase = null;
        $customers_name = Customer::orderBy('name')->get();
        $button = 'Aggiungi nuovo acquisto';
        return view('admin.purchases.create-edit', compact("title", "method", "purchase", "route", "button", "customers_name"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseRequest $request)
    {
        $form_data = $request->all();
        dump($form_data);
        // take id customer
        $customer = Customer::where('name', $request['name'])->first()->id;


        // take point to add at purchase
        $points_earned = Helper::generatePoints($request['amount']);
        dump($points_earned);

        // create purchase
        $form_data = [
            'customer_id' => $customer,
            'amount' => $request['amount'],
            'points_earned' => $points_earned,
        ];
        $new_purchase = Purchase::create($form_data);
        return redirect()->route('admin.purchases.index')->with('success', 'Acquisto aggiunto con successo');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
