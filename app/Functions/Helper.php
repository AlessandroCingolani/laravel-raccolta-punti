<?php

namespace App\Functions;


class Helper
{
    public static function generatePoints($price)
    {
        $money_for_point = 10;
        $result = floor($price / $money_for_point);
    }
}
