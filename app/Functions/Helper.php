<?php

namespace App\Functions;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Helper
{
    // value discount ten € for 1 points
    private const MONEY_FOR_POINT = 10;

    // value single coupon in €
    private const VALUE_SINGLE_COUPON = 5;

    // Solar year reference month and day
    private const START_SOLAR_MONTH = 11;

    private const START_SOLAR_DAY = 12;

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


    /**
     * Get the reference period for solar year payments.
     *
     * This method calculates the start and end dates of a solar year period based on
     * the current date. The reference period is defined by two constants:
     * - `START_SOLAR_MONTH`: The month when the solar year begins (November).
     * - `START_SOLAR_DAY`: The day when the solar year begins (12th).
     *
     * The calculation is as follows:
     * - If today's date is on or after the start date of the current year,
     *   the period starts from the defined start date of the current year
     *   and ends the day before the same date in the next year.
     * - If today's date is before the start date of the current year,
     *   the period starts from the defined start date of the previous year
     *   and ends the day before the same date in the current year.
     *
     * @return array An array containing two Carbon instances:
     *               the start date and the end date of the solar year period.
     */
    public static function getReferencePeriod()
    {
        // Today
        $today = Carbon::now();


        // Calc period of interest
        if ($today->month >= self::START_SOLAR_MONTH && $today->day >= self::START_SOLAR_DAY) {
            // From  of this year
            $startDate = Carbon::create($today->year, self::START_SOLAR_MONTH, self::START_SOLAR_DAY, 0, 0, 0);

            $endDate = Carbon::create($today->year + 1, self::START_SOLAR_MONTH, self::START_SOLAR_DAY - 1, 23, 59, 59);
        } else {

            $startDate = Carbon::create($today->year - 1, self::START_SOLAR_MONTH, self::START_SOLAR_DAY, 0, 0, 0);

            $endDate = Carbon::create($today->year, self::START_SOLAR_MONTH, self::START_SOLAR_DAY - 1, 23, 59, 59);
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
                ->groupBy('month')
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
                ->groupBy('month')
                ->pluck('sum', 'month');
            // array with month and count if month no found value put at 0
            $monthlyPurchases = array_fill(1, 12, 0);
            foreach ($purchases as $month => $sum) {
                $monthlyPurchases[$month] = $sum;
            }
            return $monthlyPurchases;
        }
    }


    public static function oldPriceWithoutCoupon($price, $n_coupon)
    {
        // formatted data into string, 2 is number after the dot
        return number_format(($price + ($n_coupon * self::VALUE_SINGLE_COUPON)), 2, '.', '');
    }
}
