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
            for ($i = 0; $i < count($operations); $i++) 
            {
                $operation = $operations[$i];

                if ($operation['type'] === 'deposit')
                {
                    echo "\n\n ------------- Deposit --------------\n";
                    echo (new Deposit)->calculate($operation)."\n";
                }
                else
                {
                    echo "\n\n\n-----------------------------------\n";
                    echo (new Withdraw)->calculate($operation, array_slice($operations, 0, $i))."\n";
                }
            }
        }
    }
}
