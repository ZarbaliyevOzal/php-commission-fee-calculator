<?php

namespace App\Models;

class CalculateCommissionFee {
    public static function calculate($amount, $rate)
    {
        return number_format(round($amount * $rate / 100, 2), 2);
    }
}