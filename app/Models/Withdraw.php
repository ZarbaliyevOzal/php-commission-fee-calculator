<?php

namespace App\Models;

class Withdraw {

    /**
     * 
     */
    public $commissionFeeBusinessClient;

    /**
     * 
     */
    public $commissionFeePrivateClient;
    
    public function __construct()
    {
        $this->commissionFeeBusinessClient = Config::get('business_clients_withdraw_commission_fee_percentage');
        $this->commissionFeePrivateClient = Config::get('private_clients_withdraw_commission_fee_percentage');
    }

    public function calculate($operation, $operations)
    {
        if ($operation['user_type'] === 'business') 
            return CalculateCommissionFee::calculate($operation['amount'], $this->commissionFeeBusinessClient);
        
        
    }
}