<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDashboard;
use App\Http\Controllers\cartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\Api\Stores\subCategories;
use App\Http\Controllers\ContentCreatorsController;
use App\Http\Controllers\Api\Admin\VerifyTransaction;
use App\Http\Controllers\Api\Stores\ProductCategories;
use App\Http\Controllers\Api\Stores\productController;
use App\Http\Controllers\Api\Stores\OrderStatusController;
use App\Http\Controllers\Api\contentCreator\BlogPostController;
use App\Http\Controllers\Api\contentCreator\BlogCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('user')->group(function(){
    Route::post('/register',[UserAuthController::class,'register']);
    Route::post('/login',[UserAuthController::class,'login']);
    Route::post('/logout',[UserAuthController::class,'logout']);
    Route::middleware('auth:user-api')->group(function(){
       //cart details 
        Route::post('/cart',[cartController::class,'store']);
        Route::get('/user-cart',[CartController::class,'index']);
        Route::put('/cart/{cart_id}/{scope}', [CartController::class, 'update']);
        Route::delete('/cart/{cart_id}', [CartController::class, 'destroy']);
        
        //order details 
        Route::post('/order',[OrderController::class,'store']);
        //dashboard 
        Route::get('/dashboard',[UserDashboard::class, 'index']);
        Route::post('/cancel_order/{order_id}',[UserDashboard::class, 'cancelOrder']);
        

     //make payment with card, this is going to be used with blade component   
        Route::post('/make-payment',[PaystackController::class,'pay']);
     //creating the comment section 
     Route::post('/comment',[CommentController::class,'store']);
     Route::delete('/comment/{id}',[CommentController::class,'destroy']);
        
    });
});

Route::prefix('admin')->group(function(){
    Route::post('/register',[AdminController::class, 'register']); 
    Route::post('/login',[AdminController::class, 'login']);
    Route::post('/logout',[AdminController::class, 'logout']); 
    Route::middleware('auth:store,admin-api')->group(function(){
       Route::get('/verify-payment',[VerifyTransaction::class,'verify_payment']);

    }); 
});



Route::prefix('auditor')->group(function(){
    Route::post('/register',[AuditorController::class, 'register']); 
    Route::post('/login',[AuditorController::class, 'login']); 
    Route::post('/logout',[AuditorController::class, 'logout']);
});

Route::prefix('contentCreator')->group(function(){
    Route::post('/register',[ContentCreatorsController::class, 'register']); 
    Route::post('/login',[ContentCreatorsController::class, 'login']); 
    Route::post('/logout',[ContentCreatorsController::class, 'logout']);
    Route::middleware('auth:contentCreator,contentCreator-api')->group(function(){
        Route::get('/blog_categories',[BlogCategoryController::class,'index']);
        Route::post('/blog_categories',[BlogCategoryController::class,'store']);
        Route::get('/blog_categories/{Blogcategory:slug}',[BlogCategoryController::class,'show']);
        Route::put('/blog_categories/{Blogcategory:slug}',[BlogCategoryController::class,'update']);
        Route::delete('/blog_categories/{Blogcategory:slug}',[BlogCategoryController::class,'destroy']);
        #blogpost
        Route::get('/blog_post',[BlogPostController::class,'index']);
        Route::post('/blog_post',[BlogPostController::class,'store']);
        Route::get('/blog_post/{BlogPost:slug}',[BlogPostController::class,'show']);
        Route::put('/blog_post/{BlogPost:slug}',[BlogPostController::class,'update']);
        Route::delete('/blog_post/{BlogPost:slug}',[BlogPostController::class,'destroy']);
    });
});

Route::prefix('store')->group(function(){
    Route::post('/register',[StoresController::class, 'register']); 
    Route::post('/login',[StoresController::class, 'login']);
    Route::post('/logout',[StoresController::class, 'logout']);  

    Route::middleware('auth:store,store-api')->group(function (){
        Route::get('/categories',[ProductCategories::class,'index']);
        Route::post('/categories',[ProductCategories::class,'store']);
        Route::get('/categories/{category:slug}',[ProductCategories::class,'show']);
        Route::put('/categories/{category:slug}',[ProductCategories::class,'update']);
        Route::delete('/categories/{category:slug}',[ProductCategories::class,'destroy']);

        Route::get('/subcategories',[subCategories::class,'index']);
        Route::put('/subcategories/{subcategory:slug}',[subCategories::class,'update']);
        Route::get('/subcategories/{subcategory:slug}',[subCategories::class,'show']);
        Route::delete('/subcategories/{subcategory:slug}',[subCategories::class,'destroy']);


        Route::post('/product',[productController::class,'store']);
        Route::get('/product',[productController::class,'index']);
        Route::get('/product/{product:slug}',[productController::class,'show']);
        Route::put('/product/{product:slug}',[productController::class,'update']);
        Route::delete('/product/{product:slug}',[productController::class,'destroy']);

        //user orders details 
        Route::get('/orders',[OrderStatusController::class,'allOrders']);
        Route::get('/cancelled-orders',[OrderStatusController::class,'cancelledOrders']);
        Route::post('/order/delivered/{order_id}',[OrderStatusController::class,'deliveredorder']);
    

    });
});


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
   
});


