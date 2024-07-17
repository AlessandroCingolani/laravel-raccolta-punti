<?php

namespace App\Http\Controllers\Admin;

use App\Functions\Helper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    // attibutes
    const MAX_EMAIL = 500;

    public function index()
    {
        // use helper function to take monthly subs
        $monthlySubscriptions = Helper::getMonthlyValues(Customer::class);

        // use helper function to take emails sends this month
        $monthlyEmailsSent = Helper::getMonthlyValues(Lead::class);

        $monthlyPurchases = Helper::getMonthlyValues(Purchase::class);
        // total amount this solar yearS
        $amount = Purchase::all()->whereBetween('created_at', Helper::getReferencePeriod())->sum('amount');

        $total_customer = Customer::count();
        $total_email_sent = Lead::count();
        $coupon_email_sent = Lead::where('type', 'coupon')->count();

        // bar data chart
        $data_bar = [
            'labels' => ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
            'data' => array_values($monthlySubscriptions),
        ];


        // donut chart
        $current_month = Carbon::now()->translatedFormat('F');
        // max email you can send

        $data_donut = [
            'labels' => ['Email Inviate', 'Email Rimanenti'],
            'data' => [$monthlyEmailsSent, self::MAX_EMAIL - $monthlyEmailsSent],
        ];



        // bar data chart
        $data_line = [
            'labels' => ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
            'data' => array_values($monthlyPurchases),
        ];

        return view("dashboard", compact("total_email_sent", "coupon_email_sent", "total_customer", "data_bar", "data_donut", "current_month", "amount", "data_line"));
    }
}