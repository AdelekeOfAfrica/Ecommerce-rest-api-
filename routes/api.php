<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\Api\Stores\subCategories;
use App\Http\Controllers\ContentCreatorsController;
use App\Http\Controllers\Api\Stores\ProductCategories;

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

Route::prefix('admin')->group(function(){
    Route::post('/register',[AdminController::class, 'register']); 
    Route::post('/login',[AdminController::class, 'login']); 
});

Route::prefix('auditor')->group(function(){
    Route::post('/register',[AuditorController::class, 'register']); 
    Route::post('/login',[AuditorController::class, 'login']); 
});

Route::prefix('auditor')->group(function(){
    Route::post('/register',[AuditorController::class, 'register']); 
    Route::post('/login',[AuditorController::class, 'login']); 
});

Route::prefix('contentCreator')->group(function(){
    Route::post('/register',[ContentCreatorsController::class, 'register']); 
    Route::post('/login',[ContentCreatorsController::class, 'login']); 
});

Route::prefix('store')->group(function(){
    Route::post('/register',[StoresController::class, 'register']); 
    Route::post('/login',[StoresController::class, 'login']); 

    Route::middleware('auth:store,store-api')->group(function (){
        Route::resource('/categories', ProductCategories::class);
        Route::resource('/subcategories', subCategories::class);

    });
});

Route::post('/login', function(){
    return 'this is working';
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
   
});


