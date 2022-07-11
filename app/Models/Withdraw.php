<?php

namespace App\Models;

use Carbon\Carbon;

class Withdraw 
{

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

        $operationAmount = $operation['amount'];

        if ($operation['currency'] !== 'EUR')
        {
            $operationAmount = (new CurrencyConverter)->convert($operation['amount'], $operation['currency'], 'EUR');
        }

        $weekStart = Carbon::parse($operation['date'])->startOfWeek(Carbon::MONDAY);
        $weekEnd = Carbon::parse($operation['date'])->endOfWeek(Carbon::SUNDAY);

        $userThisWeekPreviousOperations = array_filter($operations, function ($item) use($operation, $weekStart) {
            return $item['user_id'] === $operation['user_id'] && 
                $item['date'] >= $weekStart && 
                $item['date'] <= $operation['date'];
        });
        
        if (count($userThisWeekPreviousOperations) === 0) 
        {
            if ($operationAmount <= 1000) return 0;
            return CalculateCommissionFee::calculate($operationAmount - $this->privateClientsWithdrawLimitPerWeek, 
                $this->commissionFeePrivateClient);
        }

        $totalAmount = array_reduce($userThisWeekPreviousOperations, function($carry, $item) {
            $carry += $item['currency'] === 'EUR' 
                ? $item['amount'] 
                : (new CurrencyConverter)->convert($item['amount'], $item['currency'], 'EUR');
            return $carry;
        }, $operationAmount);

        $exceededAmount = $totalAmount - $this->privateClientsWithdrawLimitPerWeek;

        /**
         * in case it is more than 3rd time user withdraw but not exceeded the limit
         */
        if ($exceededAmount <= 0 && count($userThisWeekPreviousOperations) > 3)
        {   
            return CalculateCommissionFee::calculate($operationAmount, $this->commissionFeePrivateClient);
        }

        if ($exceededAmount > 0)
        {
            return CalculateCommissionFee::calculate($exceededAmount, $this->commissionFeePrivateClient);
        }

        return 0;
    }
}