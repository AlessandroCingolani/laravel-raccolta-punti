<?php

namespace App\Http\Controllers\Api;

use App\Functions\Helper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\GiftVoucher;
use App\Models\Purchase;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function autoComplete($search)
    {
        $search = explode(' ', $search);


        // api auto complete take search result 5 to autocomplete block
        $customers = Customer::query();
        if (count($search) === 2) {
            $firstName = $search[0];
            $lastName = $search[1];
            $customers->where('name', 'LIKE', '%' . $firstName . '%')
                ->orWhere('surname', 'LIKE', '%' . $lastName . '%');
        } else {
            $customers->where('name', 'LIKE', '%' . $search[0] . '%')
                ->orWhere('surname', 'LIKE', '%' . $search[0] . '%');
        };
        $customers = $customers->take(5)
            ->get();
        return response()->json($customers);
    }

    // Search first name or last name on table gift voucher
    public function autoCompleteGift($search)
    {
        $search = explode(' ', $search);

        $gift_vouchers = GiftVoucher::query();
        if (count($search) === 2) {
            $firstName = $search[0];
            $lastName = $search[1];
            $gift_vouchers->where('recipient_first_name', 'LIKE', '%' . $firstName . '%')
                ->orWhere('recipient_last_name', 'LIKE', '%' . $lastName . '%');
        } else {
            $gift_vouchers->where('recipient_first_name', 'LIKE', '%' . $search[0] . '%')
                ->orWhere('recipient_last_name', 'LIKE', '%' . $search[0] . '%');
        };
        $gift_vouchers = $gift_vouchers->take(5)
            ->get();
        return response()->json($gift_vouchers);
    }

    public function autoCompleteCouponCustomers($search)
    {
        $search = explode(' ', $search);

        // Query base per i clienti che hanno utilizzato i coupon
        $purchasesQuery = Purchase::with('customer')
            ->where('coupons_used', '>', 0)
            ->whereBetween('created_at', Helper::getReferencePeriod());

        // Filtra in base al nome e cognome del cliente
        if (count($search) === 2) {
            $firstName = $search[0];
            $lastName = $search[1];
            $purchasesQuery->whereHas('customer', function ($query) use ($firstName, $lastName) {
                $query->where('name', 'LIKE', '%' . $firstName . '%')
                    ->where('surname', 'LIKE', '%' . $lastName . '%');
            });
        } else {
            $namePart = $search[0];
            $purchasesQuery->whereHas('customer', function ($query) use ($namePart) {
                $query->where('name', 'LIKE', '%' . $namePart . '%')
                    ->orWhere('surname', 'LIKE', '%' . $namePart . '%');
            });
        }

        // Ottiene i dati dei clienti in base ai risultati della query
        $customers = $purchasesQuery->take(5)->get()->map(function ($purchase) {
            return [
                'name' => $purchase->customer->name,
                'surname' => $purchase->customer->surname,
                'email' => $purchase->customer->email,
                'coupons_used' => $purchase->coupons_used,
            ];
        });

        return response()->json($customers);
    }
}