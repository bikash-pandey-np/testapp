<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestC extends Controller
{
    public function index(Request $request) 
    {
         // Generate nonce
         $nonce = now()->timestamp;
        
 
         Log::info('Nonce: ' . $nonce);
 
         // Generate HMAC signature
         $hmac = $this->getHMAC($request->getContent());
 
         // Add HMAC signature to headers
         $headers = [
             'sign' => $hmac,
             'key' => env('C_API_KEY'),
             'nonce' => $nonce
         ];
         $body = ['nonce' => $nonce];
         $response = Http::withHeaders($headers)->post(env('BALANCE_URL'), $body);
 
         return $response->json();
    }

    private function getHMAC($requestBody)
    {
        $secretKey = env('C_SEC_KEY');
        $postBody = $requestBody; 

        return hash_hmac('sha512', $postBody, $secretKey);
    }
}
