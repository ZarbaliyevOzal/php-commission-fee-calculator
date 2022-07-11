<?php

namespace App\Tests;

use App\Models\Withdraw;
use PHPUnit\Framework\TestCase;

final class WithdrawTest extends TestCase 
{
    public function testClassConstructor()
    {
        $GLOBALS['config'] = [
            'business_clients_withdraw_commission_fee_percentage' => 0.5,
            'private_clients_withdraw_commission_fee_percentage' => 0.3,
            'private_clients_withdraw_limit_per_week' => 1000,
            'currency_api_key' => 'GHFinULqM12ioQPxP2qfD2A79EKnofGZ'
        ];

        $withdraw = new Withdraw();

        $this->assertIsObject($withdraw);
    }

    public function testCaldulate()
    {
        $GLOBALS['config'] = [
            'business_clients_withdraw_commission_fee_percentage' => 0.5,
            'private_clients_withdraw_commission_fee_percentage' => 0.3,
            'private_clients_withdraw_limit_per_week' => 1000,
            'currency_api_key' => 'GHFinULqM12ioQPxP2qfD2A79EKnofGZ'
        ];

        $withdraw = new Withdraw();
        
        $operations = [
            [
                'user_id' => 1,
                'user_type' => 'private',
                'type' => 'withdraw',
                'currency' => 'EUR',
                'amount' => 200,
                'date' => '2022-05-18'
            ],
            [
                'user_id' => 1,
                'user_type' => 'private',
                'type' => 'withdraw',
                'currency' => 'EUR',
                'amount' => 300,
                'date' => '2022-05-19'
            ],
            [
                'user_id' => 1,
                'user_type' => 'private',
                'type' => 'withdraw',
                'currency' => 'EUR',
                'amount' => 800,
                'date' => '2022-05-20'
            ],
        ];

        $this->assertEquals(
            $withdraw->calculate($operations[1], array_slice($operations, 0, 1)),
            0
        );

        $this->assertEquals(
            $withdraw->calculate($operations[2], array_slice($operations, 0, 2)),
            0.9
        );
    }
}