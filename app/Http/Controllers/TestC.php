<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestC extends Controller
{
    public function checkBalance(Request $request) 
    {
         // Generate nonce
         $nonce = now()->timestamp;
        
 
         Log::info('Nonce: ' . $nonce);
         
         
         $body = ['nonce' => $nonce];
         $hmac = $this->getHMAC(json_encode($body));
         // Add HMAC signature to headers
         $headers = [
             'sign' => $hmac,
             'key' => env('C_API_KEY'),
             'nonce' => $nonce
            ];
         $response = Http::withHeaders($headers)->post(env('BALANCE_URL'), $body);
 
         return $response->json();
    }

    public function withdrawDetail(Request $request)
    {
        // Generate nonce
        $nonce = now()->timestamp;
 
        Log::info('Nonce: ' . $nonce);
        
        
        $body = [
            'nonce' => $nonce,
            'cointype' => 'USDT'
        ];
        $hmac = $this->getHMAC(json_encode($body));
        // Add HMAC signature to headers
        $headers = [
            'sign' => $hmac,
            'key' => env('C_API_KEY'),
            'nonce' => $nonce
           ];
        $response = Http::withHeaders($headers)->post(env('WITHDRAW_DETAIL_URL'), $body);

        return $response->json();
    }

    public function withdraw(Request $request)
    {
        $WITHDRAW_DETAIL_URL = 'https://www.coinspot.com.au/api/v2/my/coin/withdraw/send';

        // Generate nonce
        $nonce = now()->timestamp;

        // Log the generated nonce
        Log::info('Generated Nonce: ' . $nonce);

        // Prepare the request body
        $body = [
            'nonce' => $nonce,
            'address' => "TT7BHe1dpN3SBTuz1LWdcyAgnff6LQPTEv",
            'amount' => 100,
            'cointype' => "USDT",
            'emailconfirm' => "NO",
            'network' => "LTC",
            'paymentid' => ""
        ];

        // Encode the body as JSON
        $bodyJson = json_encode($body);

        // Generate HMAC signature
        $hmac = $this->getHMAC($bodyJson);

        // Log the HMAC signature and the body JSON
        Log::info('Request Body JSON: ' . $bodyJson);
        Log::info('Generated HMAC: ' . $hmac);

        // Prepare headers
        $headers = [
            'sign' => $hmac,
            'key' => env('C_API_KEY'),
            'Content-Type' => 'application/json'
        ];

        // Send the request using Laravel's HTTP client
        $response = Http::withHeaders($headers)
            ->post($WITHDRAW_DETAIL_URL, $body);

        // Log the response for debugging
        Log::info('Response: ', $response);

        return $response->json();
    }
    private function getHMAC($requestBody)
    {
        $secretKey = env('C_SEC_KEY');
        $postBody = $requestBody; 

        return hash_hmac('sha512', $postBody, $secretKey);
    }
}
