<?php

namespace App\Http\Controllers\Api\Stores;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderStatusController extends Controller
{
    //
    public function allOrders()
    {
        $user = Auth::guard('store-api')->user();
        try{
            if ($user) {
                $order = Order::orderBy('created_at', 'desc')->paginate(10);
                return response()->json([
                    'status' => 'success',
                    'message' => $order
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not logged in'
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function cancelledOrders(){
        $user =Auth::guard('store-api')->user();
        try{
            if($user){
                $cancelled_orders = Order::where('status','canceled')->get();

                return response()->json([
                    'status'=>'success',
                    'cancelled_order'=>$cancelled_orders
                ]);
            } else{
                return response()->json([
                    'status'=>'success',
                    'message'=>'no order has been cancelled yet by the user'
                ]);
            }

        }catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 400);
      
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    public function deliveredOrder($order_id){
        $user =Auth::guard('store-api')->user();
        try{
            if($user){
                $deliver = Order::where('id',$order_id)->first();

                if (!$deliver) {
                    return response()->json([
                        'status' => 'error',
                        'error' => 'Order not found',
                    ], 404);
                }
                if($deliver){

                    $deliver->delivered_date =  DB::raw('CURRENT_DATE');
                    $deliver->status = 'delivered';
                    $deliver->save();
                    
                }
                return response()->json([
                    'status'=>'success',
                    'delivered'=>$deliver
                ]);
            } else{
                return response()->json([
                    'status'=>'success',
                    'message'=>'order not found kindly check that the order id is correct'
                ]);
            }

        }catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 400);
      
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
}
