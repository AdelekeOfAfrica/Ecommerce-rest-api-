<?php

namespace App\Http\Controllers;

use Error; 
use Exception;
use App\Models\Cart;
use App\Models\Order;
use App\Jobs\OrderJob;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email',
            'phone'=>'required|numeric',
            'companyname'=>'required',
            'country'=>'required',
            'address'=>'required',
            'city'=>'required',
            'state'=>'required',
            'postcode'=>'required',
            'payment'=>'required'
                     
        ]);
    
        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors()->first(),
            ]);
        }
    
        try{
            $user = Auth::user()->id;
            if (!Auth::check()) {
                return response()->json([
                    'errors' => 'You must be logged in to place an order.',
                ]);
            }
            $subtotal = Cart::where('user_id',$user)->sum('sub_total');
            $tax = $subtotal * 0.1;

          
            $order = new Order();
            $order->user_id = $user;
            $order->firstname = $request->firstname;
            $order->lastname = $request->lastname;
            $order->subtotal=$subtotal;
            $order->tax = $tax;
            $order->email = $request->email;
            $order->phone = $request->phone;
            $order->companyname = $request->companyname;
            $order->country = $request->country;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->postcode = $request->postcode;
            $order->save();
          
    
            if ($request->payment === "cod") {
                $transaction = new Transaction();
                $transaction->user_id = $user;
                $transaction->order_id = $order->id;
                $transaction->mode = 'cod';
                $transaction->status = 'pending';
                $transaction->save();
            } elseif ($request->payment === "card") {
                $transaction = new Transaction();
                $transaction->user_id = $user;
                $transaction->order_id = $order->id;
                $transaction->mode = 'card';
                $transaction->status = 'pending';
                $transaction->save();
            }

            OrderJob::dispatch($order)->delay(now()->addSeconds(5));
            
            return response()->json([
                 "message"=>"Order placed successfully."
            ],200);


        } catch(Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ]);
    
        } catch(Error $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ]);
        }
    }
    
}
