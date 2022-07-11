<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\Operation;

final class OperationTest extends TestCase 
{
    /**
     * Test class contructor
     */
    public function testClassConstructor()
    {
        $operation = new Operation('C:\test.csv');

        $this->assertIsString($operation->filepath);
    }

    /**
     * Test processOperations method
     */
    public function testProcessOperations()
    {
        $operation = new Operation(__DIR__.'../example.csv');

        $this->assertIsArray([]);
    }
}