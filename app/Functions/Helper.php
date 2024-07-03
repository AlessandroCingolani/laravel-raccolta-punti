<?php

namespace App\Functions;

use Carbon\Carbon;

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
}
