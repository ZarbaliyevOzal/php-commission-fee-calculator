<?php

namespace App\Models;

class Deposit {

    public $commissionFee;

    public function __construct()
    {
        $this->commissionFee = Config::get('deposit_commission_fee_percentage');
    }

    public function calculate($operation)
    {
        return CalculateCommissionFee::calculate($operation['amount'], $this->commissionFee);
    }
}