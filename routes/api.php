<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;



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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/** This is for STRIPE */
Route::post('/checkout', [PaymentController::class, 'ckeckout' ]);

/**
 * Auth Routes
 */
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'checkAuth']);
});
Route::post('/register', [AuthController::class, 'register']);    
Route::post('/login', [AuthController::class, 'login']);


/**
 * Product Routes
*/
Route::get('/product/all', [ProductController::class, 'index']);
Route::get('/product/{productId}', [ProductController::class, 'show']);
Route::get('/product/search/{name}', [ProductController::class, 'search']);

Route::get('/product/cat/{category_title}', [ProductController::class, 'get_products_by_category']);
Route::get('/product/name/{product_name}', [ProductController::class, 'get_products_by_name']);


Route::middleware(['auth:sanctum', 'isadmin'])->group( function() {
    Route::post('/product', [ProductController::class, 'store']);
    Route::post('/product/{productId}', [ProductController::class, 'update']);
    Route::delete('/product/{productId}', [ProductController::class, 'destroy']);
});

/**
 * Categories Routes
 */
Route::get('/category/all', [CategoryController::class, 'index']);
Route::get('/category/{categoryId}', [CategoryController::class, 'show']);

Route::middleware(['auth:sanctum', 'isadmin'])->group( function(){
    Route::post('/category', [CategoryController::class, 'store']);
    Route::post('/category/{categoryId}', [CategoryController::class, 'update']);
    Route::delete('/category/{categoryId}', [CategoryController::class, 'destroy']);
});

/**
 * Orders
 */
Route::middleware(['auth:sanctum', 'isuser'])->group(function() {
    Route::post('/order/create', [OrderController::class, 'create']);
    Route::get('/order/user', [OrderController::class, 'orders_for_one_user']);
});

Route::middleware(['auth:sanctum', 'isadmin'])->group(function() {
    Route::post('/order/change/status', [OrderController::class, 'order_status']);
    Route::get('/orders/pending', [OrderController::class, 'get_pendings_orders']);
    Route::get('/orders/sended', [OrderController::class, 'get_sended_orders']);
});
