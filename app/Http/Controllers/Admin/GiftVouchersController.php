<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiftVoucherRequest;
use Illuminate\Http\Request;
use App\Models\GiftVoucher;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GiftVouchersController extends Controller
{
    // Durata validità del coupon
    const MONTH_VOUCHER_VALIDATION = 2;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = GiftVoucher::paginate(10);
        return view('admin.gift_vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Aggiungi buono regalo";
        $method = 'POST';
        $route = route('admin.gift_vouchers.store');
        $gift_voucher = null;
        $button = 'Crea nuovo buono regalo';

        return view('admin.gift_vouchers.create-edit', compact('title', 'method', 'route', 'button', 'gift_voucher'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GiftVoucherRequest $request)
    {
        $form_data = $request->all();

        $uniqueCode = $this->generateUniqueCode();
        $form_data['code'] = $uniqueCode;

        // expiration time for coupon
        $expirationDate = Carbon::now()->addMonths(self::MONTH_VOUCHER_VALIDATION);
        $form_data['expiration_date'] = $expirationDate;

        $new_gift_voucher = GiftVoucher::create($form_data);

        return redirect()->route('admin.gift_vouchers.show', $new_gift_voucher);
    }

    /**
     * Display the specified resource.
     */
    public function show(GiftVoucher $gift_voucher)
    {
        return view('admin.gift_vouchers.show', compact('gift_voucher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GiftVoucher $gift_voucher)
    {
        $title = "Modifica buono regalo";
        $method = 'PUT';
        $route = route('admin.gift_vouchers.update', $gift_voucher);
        $button = 'Modifica buono regalo';
        return view('admin.gift_vouchers.create-edit', compact('title', 'method', 'route', 'button', 'gift_voucher'));
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

    /**
     * Genera un codice unico per il buono regalo
     *
     * @return string
     */
    private function generateUniqueCode()
    {
        // generate unique code voucher
        $code = Str::random(15);

        // if code exist generate another code
        while (GiftVoucher::where('code', $code)->exists()) {
            $code = Str::random(15);
        }

        return $code;
    }
}
