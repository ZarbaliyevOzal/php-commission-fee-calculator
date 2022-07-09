<?php

// if (!isset($argc)) {
//   die("argc and argv disabled");
//   exit();
// }

// if (!isset($argv[1])) {
//   die('Please provide input file');
//   exit();
// }

// $inputFile = $argv[1];

// echo $inputFile;


// public function readCsv($filepath)
// {
//     $arr = []; 

//     // Open the file for reading
//     if (($h = fopen("{$filepath}", "r")) !== FALSE) 
//     {
//         while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
//         {
//             // Each individual array is being pushed into the nested array
//             $arr[] = [
//                 'user_id' => $data[1],
//                 'user_type' => $data[2],
//                 'operation_type' => $data[3],
//                 'operation_amount' => $data[4],
//                 'currency' => $data[5],
//                 'date' => $data[0]
//             ];		
//         }

//         // Close the file
//         fclose($h);
//     }

//     // Display the code in a readable format
//     return $arr;
// }

require 'app/bootstrap.php';
use App\Models\OperationFeeCalculator;

$filepath = __DIR__.'/'.$argv[1];

$o = new OperationFeeCalculator($filepath);

echo $o->processOperations();