<?php

namespace App\Models;

trait Csv
{
    /**
     * Read csv file
     * @param {String}  $filepath
     * @return Array
     */
    public function readCsv($filepath)
    {
        $arr = []; 

        // Open the file for reading
        if (($h = fopen("{$filepath}", "r")) !== FALSE) 
        {
            while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
            {
                // Each individual array is being pushed into the nested array
                $arr[] = [
                    'user_id' => $data[1],
                    'user_type' => $data[2],
                    'type' => $data[3],
                    'amount' => $data[4],
                    'currency' => $data[5],
                    'date' => $data[0]
                ];		
            }

            // Close the file
            fclose($h);
        }

        // Display the code in a readable format
        return $arr;
    }
}