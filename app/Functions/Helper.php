<?php

namespace App\Functions;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Helper
{
    // value discount
    const MONEY_FOR_POINT = 10;
    // point generator
    public static function generatePoints($price)
    {

        return floor($price / self::MONEY_FOR_POINT);
    }

    // convert coupon into point
    public static function couponToPoints($coupons)
    {
        return $coupons * self::MONEY_FOR_POINT;
    }

    // take create at and formatted italian display d/m/y
    public static function formatDate($date)
    {
        $new_date = date_create($date);
        return date_format($new_date, 'd/m/Y');
    }

    // calc discount aviable
    public static function discountCoupons($total_points)
    {
        return intval(floor($total_points / self::MONEY_FOR_POINT));
    }


    // take start and end date to take solar year payment and other
    public static function getReferencePeriod()
    {
        // Today
        $today = Carbon::now();

        // Calc period of interest
        if ($today->day >= 12) {
            // From 12 november of this year
            $startDate = Carbon::create($today->year, 11, 12, 0, 0, 0);
            // At 11 november of next year
            $endDate = Carbon::create($today->year + 1, 11, 11, 23, 59, 59);
        } else {
            // From 12 november of last year
            $startDate = Carbon::create($today->year - 1, 11, 12, 0, 0, 0);
            // At 11 november of this year
            $endDate = Carbon::create($today->year, 11, 11, 23, 59, 59);
        }
        return [$startDate, $endDate];
    }


    /**
     *
     */

    public static function getMonthlyValues($model)
    {
        // this year
        $currentYear = Carbon::now()->year;
        // this month
        $currentMonth = Carbon::now()->month;

        // take iscription monthly this year bar chart
        if ($model  === Customer::class) {
            // Query for take iscription
            $subscriptions = Customer::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
                ->whereYear('created_at', $currentYear)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('count', 'month');

            // array with month and count if month no found value put at 0
            $monthlySubscriptions = array_fill(1, 12, 0);
            foreach ($subscriptions as $month => $count) {
                $monthlySubscriptions[$month] = $count;
            }
            return $monthlySubscriptions;
        }


        // sended email this month donut chart
        if ($model === Lead::class) {
            $emailsSentThisMonth = DB::table('leads')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->count();

            return $emailsSentThisMonth;
        }

        // take sum monthly line chart
        if ($model === Purchase::class) {
            $purchases = Purchase::select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as sum'))
                ->whereYear('created_at', $currentYear)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('sum', 'month');

            // array with month and count if month no found value put at 0
            $monthlyPurchases = array_fill(1, 12, 0);
            foreach ($purchases as $month => $sum) {
                $monthlyPurchases[$month] = $sum;
            }
            return $monthlyPurchases;
        }
    }
}
