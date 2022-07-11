<?php
require 'app/bootstrap.php';

use App\Models\Operation;

$filepath = __DIR__.'/'.$argv[1];

$operation = new Operation($filepath);

echo $operation->processOperations();