<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\GiftVoucher;
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
}