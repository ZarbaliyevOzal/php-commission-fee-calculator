<?php

namespace App\Models;

class OperationFeeCalculator 
{
    use Config, Csv;
    
    /**
     * input file containing operations
     */
    public $filepath;

    /**
     * deposit amount are charged by
     */
    public $depositCommissionFeePercentage;

    public $businessClientsWithdrawCommissionFeePercentage;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
        $this->depositCommissionFeePercentage = $this->config('deposit_commission_fee_percentage');
        $this->businessClientsWithdrawCommissionFeePercentage = 
            $this->config('business_clients_withdraw_commission_fee_percentage');
    }

    /**
     * calculate
     */
    public function processOperations()
    {
        $operations = $this->readCsv($this->filepath);
        if (count($operations) > 0) 
        {
            foreach ($operations as $operation) 
            {
                if ($operation['type'] === 'deposit')
                {
                    echo $this->calculateCommissionFee($operation['amount'], $this->depositCommissionFeePercentage)."\n";
                }
                else
                {
                    echo $this->calculateWithdrawCommissionFee($operation)."\n";
                }
            }
        }
    }

    /**
     * Calculate deposit fee
     * @param {Float}  $amount
     * @return {Float}
     */
    public function calculateCommissionFee($amount, $fee)
    {
        return number_format(round($amount * $fee / 100, 2), 2);
    }

    /**
     * 
     */
    public function calculateWithdrawCommissionFee($operation)
    {
        if ($operation['user_type'] === 'business')
        {
            return $this->calculateCommissionFee(
                $operation['amount'], 
                $this->businessClientsWithdrawCommissionFeePercentage
            );
        }
        else
        {
            //
        }
    }
}
