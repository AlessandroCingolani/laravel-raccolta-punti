<?php

namespace App\Functions;


class Helper
{
    public static function generatePoints($price)
    {
        $money_for_point = 10;
        return floor($price / $money_for_point);
    }

    public static function formatDate($date)
    {
        $new_date = date_create($date);
        return date_format($new_date, 'd/m/Y');
    }
}
