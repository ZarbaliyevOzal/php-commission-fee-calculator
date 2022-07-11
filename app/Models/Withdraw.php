<?php

namespace App\Models;

use Carbon\Carbon;

class Withdraw {

    /**
     * Commision fee for business clients
     */
    public $commissionFeeBusinessClient;

    /**
     * Commission fee for private clients
     */
    public $commissionFeePrivateClient;

    /**
     * Private clients withdraw limit per week
     */
    public $privateClientsWithdrawLimitPerWeek;
    

    public function __construct()
    {
        $this->commissionFeeBusinessClient = Config::get('business_clients_withdraw_commission_fee_percentage');
        $this->commissionFeePrivateClient = Config::get('private_clients_withdraw_commission_fee_percentage');
        $this->privateClientsWithdrawLimitPerWeek = Config::get('private_clients_withdraw_limit_per_week');
    }

    /**
     * Calculate withdraw commission fee
     * @param {Object}  $operation
     * @param {Array}  $operations
     * @return {String}
     */
    public function calculate($operation, $operations)
    {
        if ($operation['user_type'] === 'business') 
            return CalculateCommissionFee::calculate($operation['amount'], $this->commissionFeeBusinessClient);

        $weekStart = Carbon::parse($operation['date'])->startOfWeek(Carbon::MONDAY);
        $weekEnd = Carbon::parse($operation['date'])->endOfWeek(Carbon::SUNDAY);

        $userThisWeekPreviousOperations = array_filter($operations, function ($item) use($operation, $weekStart) {
            return $item['user_id'] === $operation['user_id'] && 
                $item['date'] >= $weekStart && 
                $item['date'] <= $operation['date'];
        });
        
        if (count($userThisWeekPreviousOperations) === 0) 
        {
            if ($operation['amount'] <= 1000) return 0;
            return CalculateCommissionFee::calculate($operation['amount'] - $this->privateClientsWithdrawLimitPerWeek, 
                $this->commissionFeePrivateClient);
        }

        $totalAmount = array_reduce($userThisWeekPreviousOperations, function($carry, $item) {
            $carry += $item['amount'];
            return $carry;
        }, $operation['amount']);

        $exceededAmount = $totalAmount - $this->privateClientsWithdrawLimitPerWeek;

        /**
         * in case it is more than 3rd time user withdraw but not exceeded the limit
         */
        if ($exceededAmount <= 0 && count($userThisWeekPreviousOperations) > 3)
        {   
            return CalculateCommissionFee::calculate($operation['amount'], $this->commissionFeePrivateClient);
        }

        if ($exceededAmount > 0)
        {
            return CalculateCommissionFee::calculate($exceededAmount, $this->commissionFeePrivateClient);
        }

        // var_dump($exceededAmount);
        // var_dump('operation date: ' . $operation['date']);
        // var_dump('start of the week: ' . $weekStart);
        // var_dump('end of the week: ' . $weekEnd);

        return 0;
    }
}