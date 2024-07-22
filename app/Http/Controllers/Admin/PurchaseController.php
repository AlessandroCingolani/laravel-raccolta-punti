<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;
use App\Functions\Helper;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $purchases = Purchase::with('customer')
            ->orderBy('id', 'desc')
            ->whereBetween('created_at', Helper::getReferencePeriod())
            ->paginate(10);
        return view('admin.purchases.index', compact('purchases'));
    }



    public function couponsUsed(Request $request)
    {
        $purchases = Purchase::with('customer')
            ->orderBy('id', 'desc')
            ->where('coupons_used', '>', 0)
            ->whereBetween('created_at', Helper::getReferencePeriod());


        // if request has tosearch input
        if ($request->has('tosearch')) {
            $search = $request->input('tosearch');

            // add at purchases wherehas customer who have name and surname tosearch
            $purchases->whereHas('customer', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('surname', 'LIKE', '%' . $search . '%');
            });
        }

        $purchases = $request->has('tosearch') ? $purchases->paginate(10)->appends(['tosearch' => $request['tosearch']]) : $purchases->paginate(10);


        return view('admin.purchases.coupons-used', compact('purchases'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // take if is not null customer from the request else null
        $customer_selected = !is_null($request['id']) ? Customer::find($request['id']) : null;
        // take coupoms if 10 points use helper to generate how many coupons else 0 coupons
        $coupons = $customer_selected?->customer_points >= 10 ? Helper::discountCoupons($customer_selected->customer_points) : 0;

        $title = "Aggiungi acquisto";
        $method = "POST";
        $route = route("admin.purchases.store");
        $purchase = null;
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
            'coupons_used' => isset($request['coupon']) && !is_null($request['coupon']) ? $request['coupon'] : 0
        ];
        // take customer from id
        $customer = Customer::find($customer_id);
        $new_purchase = Purchase::create($form_data);



        // update column customer points if point earned > 0
        if ($points_earned > 0) {
            $customer->update(array('customer_points' => ($customer->customer_points + $points_earned)));
        }


        if (isset($request['coupon'])) {
            $customer->update(array('customer_points' => ($customer->customer_points - Helper::couponToPoints($request['coupon']))));
        }

        return redirect()->route('admin.customers.show', $customer_id)->with('success', 'Acquisto aggiunto con successo!');
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

        // TODO: fix quando editi e cambi la persona che ha fatto l acquisto il saldo totale rimane giusto ma i punti non vengono spostati da il precedente a quello nuovo, quindi se cambi all' edit il cliente di quel pagamento modifica soltanto il totale degli acquisti. Necessita un controllo sull id dell utente se cambia di fare un passaggio di punti con eventuali controlli
        $title = "Modifica acquisto";
        $method = "PUT";
        $route = route("admin.purchases.update", $purchase);
        $customer_selected = null;
        $coupons = 0;
        $customer = Customer::find($purchase->customer_id);
        if ($customer->customer_points >= 10) {
            $coupons = Helper::discountCoupons($customer->customer_points);
        }
        $customers_name = Customer::orderBy('name')->get();
        $button = 'Modifica acquisto';
        return view('admin.purchases.create-edit', compact("title", "method", "purchase", "route", "button", "customers_name", "customer_selected", "coupons"));
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


        if ($customer->customer_points - $purchase->points_earned >= 0) {
            $customer->update(array('customer_points' => ($customer->customer_points - $purchase->points_earned)));
        }
        $purchase->delete();
        return back()->with('success', 'Acquisto eliminato con successo');
    }
}
