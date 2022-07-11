<?php

namespace App\Tests;

use App\Models\Csv;
use PHPUnit\Framework\TestCase;

class CsvTest extends TestCase 
{

    use Csv;

    public function testReadCsv()
    {
        $arr = $this->readCsv(__DIR__.'/../example.csv');
        $this->assertIsArray($arr);
    }
}