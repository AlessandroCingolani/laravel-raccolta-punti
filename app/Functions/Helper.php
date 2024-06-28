<?php

namespace App\Functions;


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
}
