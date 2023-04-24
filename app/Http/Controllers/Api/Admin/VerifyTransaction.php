<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifyTransaction extends Controller
{
    //
    public function verify_payment(Request $request)
    {
        $reference = $request->reference;
        $result = $this->callback($reference);
       return $result;
        
    }
    

    public function callback($reference)
    {
        
    
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "Authorization: Bearer sk_test_69ab30e3e0d796f665fef7e986eb8729bf837b6b",
              "Cache-Control: no-cache",
            )
          ));
          
    
        $response = curl_exec($curl);
        
        $error = curl_error($curl);
        curl_close($curl);
    
        if ($error) {
            // Handle cURL error appropriately
            return json_encode([
                'status' => false,
                'message' => 'cURL error: ' . $error,
            ]);
        }
    
        return  $response; 
             
    }
    
}
