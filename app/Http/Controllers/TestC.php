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
        // Generate nonce
        $nonce = now()->timestamp;
 
        Log::info('Nonce: ' . $nonce);
        
        
        $body = [
            'nonce' => $nonce,
            'address' => "TT7BHe1dpN3SBTuz1LWdcyAgnff6LQPTEv",
            'amount' => 100,
            'cointype' => "USDT",
            'emailconfirm' => "NO",
            'network' => "LTC",
            'paymentid' => ""
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

    private function getHMAC($requestBody)
    {
        $secretKey = env('C_SEC_KEY');
        $postBody = $requestBody; 

        return hash_hmac('sha512', $postBody, $secretKey);
    }
}
