<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GiftVoucherRequest;
use Illuminate\Http\Request;
use App\Models\GiftVoucher;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Gitignore;

class GiftVouchersController extends Controller
{
    // Duration coupon
    const MOUNTH_VOUCHER_VALIDATION = 2;


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Lista buoni regalo attivi";
        $vouchers = GiftVoucher::where("status", "valid")->paginate(10);
        return view('admin.gift_vouchers.index', compact('vouchers', 'title'));
    }


    //  Display used Gift voucher
    public function usedGift()
    {
        $title = "Lista buoni regalo utilizzati";
        $vouchers = GiftVoucher::where("status", "used")->paginate(10);
        return view('admin.gift_vouchers.index', compact('vouchers', 'title'));
    }

    //  Display expired Gift voucher
    public function expiredGift()
    {
        $title = "Lista buoni regalo scaduti";
        $vouchers = GiftVoucher::where("status", "expired")->paginate(10);
        return view('admin.gift_vouchers.index', compact('vouchers', 'title'));
    }


    public function searchGiftCustomers(Request $request)
    {
        $searchValue = $request->input('tosearch');
        $fullName = explode(' ', $searchValue);
        $title = "Lista buoni regalo";

        // Crea una query di base senza eseguire immediatamente
        $vouchers = GiftVoucher::query();

        if (count($fullName) === 2) {
            // Cerca combinando nome e cognome
            $firstName = $fullName[0];
            $lastName = $fullName[1];
            $vouchers->where('recipient_first_name', 'LIKE', '%' . $firstName . '%')
                ->where('recipient_last_name', 'LIKE', '%' . $lastName . '%');
        } else {
            // Cerca parzialmente per nome o cognome
            $vouchers->where('recipient_first_name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('recipient_last_name', 'LIKE', '%' . $searchValue . '%');
        }

        $vouchers = $vouchers->paginate(10);

        return view('admin.gift_vouchers.index', compact('vouchers', 'title'));
    }

    // Funzione per utilizzare i Gift Vouchers
    public function toggleStatus(GiftVoucher $voucher)
    {
        // Cambia lo stato con un ternario se è valid mette used sennò è valid
        if ($voucher->status != 'expired') {
            $voucher->status === 'valid' ? $voucher->status = 'used' : $voucher->status = 'valid';
            $voucher->save();
            return redirect()->route('admin.gift_vouchers.index')
                ->with('success', 'Stato del gift voucher aggiornato');
        }
        // Manda errore nel caso di modifica del buono scaduto
        return redirect()->route('admin.gift_vouchers.index')
            ->withErrors('Non puoi modificare questo buono');
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

        $form_data['recipient_first_name'] = ucwords($form_data['recipient_first_name']);
        $form_data['recipient_last_name'] = ucwords($form_data['recipient_last_name']);

        $uniqueCode = $this->generateUniqueCode();
        $form_data['code'] = $uniqueCode;

        // expiration time for coupon
        $expirationDate = Carbon::now()->addMonths(self::MOUNTH_VOUCHER_VALIDATION);
        $form_data['expiration_date'] = $expirationDate;

        $new_gift_voucher = GiftVoucher::create($form_data);

        return redirect()->route('admin.gift_vouchers.show', $new_gift_voucher);
    }

    /**
     * Display the specified resource.
     */
    public function show(GiftVoucher $gift_voucher)
    {
        $monthsToAdd = self::MOUNTH_VOUCHER_VALIDATION;
        return view('admin.gift_vouchers.show', compact('gift_voucher', 'monthsToAdd'));
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
    public function update(GiftVoucherRequest $request, GiftVoucher $gift_voucher)
    {
        // TODO: Controlla che il valore del codice rimane uguale dopo l update ho impostato un campo invisibile per portare il vecchio codice se esiste
        $form_data = $request->all();

        $form_data['recipient_first_name'] = ucwords($form_data['recipient_first_name']);
        $form_data['recipient_last_name'] = ucwords($form_data['recipient_last_name']);

        $gift_voucher->update($form_data);

        return redirect()->route('admin.gift_vouchers.show', $gift_voucher)->with('success', 'Buono regalo modificato con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GiftVoucher $gift_voucher)
    {
        $gift_voucher->delete();
        return redirect()->route('admin.gift_vouchers.index')->with('success', 'Buono regalo eliminato con successo');
    }

    /**
     * Generate unique code
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