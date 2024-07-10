<?php

namespace App\Http\Controllers\Admin;

use App\Functions\Helper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // use helper function to take monthly subs
        $monthlySubscriptions = Helper::getMonthlyValues(Customer::class);

        // use helper function to take emails sends this month
        $monthlyEmailsSent = Helper::getMonthlyValues(Lead::class);

        $total_customer = Customer::count();
        $total_email_sent = Lead::count();
        $coupon_email_sent = Lead::where('type', 'coupon')->count();

        // bar data chart
        $data_bar = [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'data' => array_values($monthlySubscriptions),
        ];


        // donut chart
        $current_month = Carbon::now()->translatedFormat('F');
        // max email you can send
        $maxEmails = 500;
        $data_donut = [
            'labels' => ['Email Inviate', 'Email Rimanenti'],
            'data' => [$monthlyEmailsSent, $maxEmails - $monthlyEmailsSent],
        ];

        return view("dashboard", compact("total_email_sent", "coupon_email_sent", "total_customer", "data_bar", "data_donut", "current_month"));
    }
}
