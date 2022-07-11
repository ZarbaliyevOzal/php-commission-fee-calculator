<?php

namespace App\Models;

class Operation
{
    use Csv;
    
    /**
     * input file containing operations
     */
    public $filepath;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * Process operations
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
                    echo (new Deposit)->calculate($operation)."\n";
                }
                else
                {
                    // echo $this->calculateWithdrawCommissionFee($operation)."\n";
                }
            }
        }
    }
}
