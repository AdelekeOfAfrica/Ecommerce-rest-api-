<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\Api\Stores\subCategories;
use App\Http\Controllers\ContentCreatorsController;
use App\Http\Controllers\Api\Stores\ProductCategories;
use App\Http\Controllers\Api\Stores\productController;

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
    });
});

Route::prefix('admin')->group(function(){
    Route::post('/register',[AdminController::class, 'register']); 
    Route::post('/login',[AdminController::class, 'login']);
    Route::post('/logout',[AdminController::class, 'logout']);  
});

Route::prefix('auditor')->group(function(){
    Route::post('/register',[AuditorController::class, 'register']); 
    Route::post('/login',[AuditorController::class, 'login']);
    Route::post('/logout',[AuditorController::class, 'logout']); 
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
    

    });
});

Route::post('/login', function(){
    return 'this is working';
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
   
});


