<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserDashboard extends Controller
{
    //
    public function index(){
        $user = Auth::user()->id;

        try{
        
            if($user){
                $user_orders = Order::where('user_id',$user)->paginate(10);
                return response()->json([
                    "status"=>"success",
                    "message"=>$user_orders,
                ]);
            }

        }   catch(Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ]);
    
        } catch(Error $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function cancelOrder($order_id) {
    
        try {
            $user_id = Auth::user()->id;
    
            $order = Order::where('user_id', $user_id)
                ->where('id', $order_id)
                ->first();
    
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'Order not found',
                ], 404);
            }
            if($order){

                $order->cancelled_date =  DB::raw('CURRENT_DATE');
                $order->status = 'canceled';
                $order->save();
                
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'Order successfully cancelled',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'error' => 'Order not found',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

}
