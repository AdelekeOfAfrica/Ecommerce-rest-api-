<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaystackController extends Controller
{
    //

    public function pay (){

        $user = Auth::user()->id ;
        $order_details = Order::where('user_id',$user)->orderBy('created_at', 'desc')->first();
        if($user){
            $formdata = [
                'email'=>$order_details->email,
                'amount'=>$order_details->subtotal * 100,
            ];
            $pay =$this->initiate_payment($formdata);
            $pay = json_decode($pay);
            dd($pay); #this is to be done only if you are using postman, to get the reference code 
            if($pay){
                if($pay->status) {
            
                  return redirect($pay->data->authorization_url);
            
                }else {
                    return back()->withError($pay->message);
            
                }
            }else{
                return back()->withError("something went wrong");
            }
            return response()->json([
                "message"=>"you have acccess",
            ],200);

        }else{
            return response()->json([
                "error"=>"kindly login to access this page"
            ],401);
        }

    }

    public function initiate_payment($formdata) {
        $url = "https://api.paystack.co/transaction/initialize";
        $field_String = http_build_query($formdata);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $field_String); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . env("PAYSTACK_SECRET_KEY"),
            "Cache-Control: no-cache"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return "Curl error: " . $error_msg;
        }
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_status >= 400) {
            return "HTTP error: " . $http_status;
        }
        $response = json_decode($result);
        if (!$response->status) {
            return "API error: " . $response->message;
        }
        return $result;
    }


    
             
}
