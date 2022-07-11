<?php

namespace App\Tests;

use App\Models\Deposit;
use PHPUnit\Framework\TestCase;

final class DepositTest extends TestCase 
{
    public function testClassConstructor()
    {
        $GLOBALS['config'] = [
            'deposit_commission_fee_percentage' => 0.03
        ];

        $deposit = new Deposit();
        
        $this->assertIsNumeric($deposit->commissionFee);
    }

    public function testCalculate()
    {
        $GLOBALS['config'] = [
            'deposit_commission_fee_percentage' => 0.03
        ];

        $deposit = new Deposit();

        $operation = [
            'user_id' => 1,
            'user_type' => 'private',
            'type' => 'deposit',
            'currency' => 'EUR',
            'amount' => 1200,
            'date' => '2022-05-20'
        ];

        $this->assertIsNumeric($deposit->calculate($operation));
    }
}