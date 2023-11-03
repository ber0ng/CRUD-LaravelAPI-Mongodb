<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Products
Route::controller(ProductController::class)->group(function (){
    Route::get('/products', 'index');
    Route::post('/admin/product', 'store');
    Route::put('/admin/product/{product_id}', 'update');
    Route::delete('/admin/product/{product_id}', 'destroy');
    Route::get('/getproduct/{product_id}', 'getProduct');
    Route::get('/products/search', 'search');
    Route::get('/products/filter', 'filter');
    Route::delete('/admin/products/deleteAll', 'destroyAll');
    
});

//Users
Route::controller(UserController::class)->group(function (){
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/admin/getAllUser', 'getAllUser');
    Route::delete('/admin/deleteUser/{user_id}', 'deleteUser');

});

//Orders
Route::controller(OrderController::class)->group(function(){
    Route::post('/order', 'createOrder');
});




