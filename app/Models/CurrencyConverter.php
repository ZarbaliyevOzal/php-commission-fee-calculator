<?php

namespace App\Models;

class CurrencyConverter {
    /**
     * Convert currency 
     * @param {Float|Int}  $amount
     * @param {String}  $from
     * @param {String}  $to 
     * @return {Float|Int}
     */
    public function convert($amount, $from, $to)
    {
        $apiKey = Config::get('currency_api_key');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/convert?to={$to}&from={$from}&amount={$amount}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: {$apiKey}"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        if (!isset($response->success))
        {
            echo 'Currency api server error';
            die();
            exit();
            return;
        }

        if (!$response->success) {
            echo $response->message || 'Currency api server error';
            die();
            exit();
            return;
        }

        return $response->result;
    }
}