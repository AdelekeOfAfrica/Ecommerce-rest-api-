<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CartResource;

class cartController extends Controller
{

    public function index()
    {
        $user_id = auth()->user()->id;
    
        $carts = Cart::where('user_id', $user_id)->get();
    
        // If there are no carts with products, return a message
        if ($carts->isEmpty()) {
            return new CartResource(null);
        }
    
        return new CartResource($carts);
    }
   



    //to run the below function in postman
    // "cart_items": [
    //     {
    //         "product_id": 1,
    //         "product_qty": 3
    //     },
    //     {
    //         "product_id": 2,
    //         "product_qty": 2
    //     }
    // ]
    public function store(Request $request) {
        if($user = auth()->user()){
            $user_id = auth()->user()->id;
            $cart_items = $request->cart_items; // array of products and quantities
    
            foreach($cart_items as $item) {
                $product_id = $item['product_id'];
                $product_qty = $item['product_qty'];
    
                $productcheck = Products::where('id',$product_id)->first();
                if($productcheck){
                    if(Cart::where('product_id',$product_id)->where('user_id',$user_id)->exists()){
                        return response()->json([
                            'status'=>409,
                            'message'=>$productcheck->name, 'product already added to the cart'
                        ]);
                    }
                    else{
                        $cartitem = new Cart;
                        $cartitem->user_id = $user->id;
                        $cartitem->product_id = $product_id;
                        $cartitem->product_qty = $product_qty;
                        $cartitem->product_price = $productcheck->regular_price;
                        $cartitem->sub_total = $productcheck->regular_price * $product_qty; // calculate total price
                        $cartitem->save();
                    }
                }
                else{
                    return response()->json([
                        'status'=>409,
                        'message'=>'product does not exist'
                    ]);
                }
            }
    
            return response()->json([
                'status'=>200,
                'message'=>'products added to cart'
            ]);
        }
        else {
            return response()->json([
                "message"=>"user needs to be logged in first"
            ]);
        }
    } 
    #localhost:8000/api/user/cart/2/dec
    public function update(Request $request, $cart_id, $scope)
    {
        if ($user = auth()->user()) {
            $user_id = auth()->user()->id;
            $cart_item = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();
    
            if ($cart_item) {
                if ($scope == "inc") {
                    $cart_item->product_qty += 1;
                } elseif ($scope == "dec") {
                    if ($cart_item->product_qty == 0) {
                        $cart_item->delete();
                        return response()->json([
                            'status' => 200,
                            'message' => "product removed from cart"
                        ]);
                    } else {
                        $cart_item->product_qty -= 1;
                    }
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => "invalid scope"
                    ]);
                }
    
                $productcheck = Products::where('id', $cart_item->product_id)->first();
                if ($productcheck) {
                    $cart_item->product_price = $productcheck->regular_price;
                    $cart_item->sub_total = $productcheck->regular_price * $cart_item->product_qty; // calculate total price
                    $cart_item->save();
    
                    return response()->json([
                        'status' => 200,
                        'message' => "quantity updated successfully"
                    ]);
                } else {
                    return response()->json([
                        'status' => 409,
                        'message' => 'product does not exist'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 409,
                    'message' => 'cart item does not exist'
                ]);
            }
        } else {
            return response()->json([
                "message" => "user needs to be logged in first"
            ]);
        }
    
        
    }

     #delete function 
     public function destroy($cart_id)
     {
         //
 
         if($user=auth()->user()){
             $user_id = auth()->user()->id;
             $cartitem=cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($cartitem){
                $cartitem->delete();
                return response()->json([
                 'status'=>200,
                 'message'=>"cart deleted"
             ]);
            }
         
 
         }else{
             return response()->json([
                 'status'=>403,
                 'message'=>"you have to login first"
             ]);
         }
         
     }
    
}

