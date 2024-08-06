<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'admin'], function(){
    Route::post('login', [\App\Http\Controllers\API\v1\Admin\AuthController::class,'login']);
    Route::post('logout', [\App\Http\Controllers\API\v1\Admin\AuthController::class,'logout']);

    Route::get('me', [\App\Http\Controllers\API\v1\Admin\AuthController::class,'me']);

    Route::post('refresh', [\App\Http\Controllers\API\v1\Admin\AuthController::class,'refresh']);

    Route::get('dashboard', [\App\Http\Controllers\API\v1\Admin\DashboardController::class,'index']);
    // Orders
    Route::get('orders', [\App\Http\Controllers\API\v1\Admin\OrderController::class,'index']);
    Route::get('orders/{id}', [\App\Http\Controllers\API\v1\Admin\OrderController::class,'show']);
    Route::patch('orders/{id}',[\App\Http\Controllers\API\v1\Admin\OrderController::class,'updateOrderStatus']);

    // seller management
    Route::get('sellers',[\App\Http\Controllers\API\v1\Admin\SellerController::class,'index']);
    Route::get('sellers/{id}', [\App\Http\Controllers\API\v1\Admin\SellerController::class,'show']);
    Route::patch('sellers/{id}/ban', [\App\Http\Controllers\API\v1\Admin\SellerController::class,'ban']);
    Route::patch('sellers/{id}/activate', [\App\Http\Controllers\API\v1\Admin\SellerController::class,'activate']);

    // store management
    Route::get('stores', [\App\Http\Controllers\API\v1\Admin\StoreController::class,'index']);
    Route::get('stores/{id}', [\App\Http\Controllers\API\v1\Admin\StoreController::class,'show']);
    Route::patch('stores/{id}/ban', [\App\Http\Controllers\API\v1\Admin\StoreController::class,'ban']);
    Route::patch('stores/{id}/activate', [\App\Http\Controllers\API\v1\Admin\StoreController::class,'activate']);

});


Route::group(['prefix' => 'seller'], function() {
    Route::post("register", [\App\Http\Controllers\API\v1\Seller\AuthController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\API\v1\Seller\AuthController::class, 'login']);

    Route::post('logout', [\App\Http\Controllers\API\v1\Seller\AuthController::class, 'logout']);

    Route::post('refresh', [\App\Http\Controllers\API\v1\Seller\AuthController::class, 'refresh']);
    Route::get('me', [\App\Http\Controllers\API\v1\Seller\AuthController::class, 'me']);

    Route::get('dashboard',[\App\Http\Controllers\API\v1\Seller\DashboardController::class,'index']);


    Route::get('stores', [\App\Http\Controllers\API\v1\Seller\StoreController::class, 'index'])->name('seller.stores.index');
    Route::post('stores', [\App\Http\Controllers\API\v1\Seller\StoreController::class, 'store'])->name('seller.stores.store');

    Route::get('products/{store_id}', [\App\Http\Controllers\API\v1\Seller\ProductController::class, 'index']);

    Route::post('products', [\App\Http\Controllers\API\v1\Seller\ProductController::class, 'store']);

    Route::get('orders',[\App\Http\Controllers\API\v1\Seller\OrderController::class,'index']);
    Route::get('orders/{id}', [\App\Http\Controllers\API\v1\Seller\OrderController::class,'getOrder']);
    Route::patch('orders/{id}', [\App\Http\Controllers\API\v1\Seller\OrderController::class,'updateOrderStatus']);
    Route::patch('orders/{id}/complete',[\App\Http\Controllers\API\v1\Seller\OrderController::class,'setOrderToComplete']);
});



Route::group(['prefix' => 'auth'], function(){
    Route::post('register', [\App\Http\Controllers\API\v1\AuthController::class,'register']);
    Route::post('login', [\App\Http\Controllers\API\v1\AuthController::class,'login']);
    Route::post('logout', [\App\Http\Controllers\API\v1\AuthController::class,'logout']);

    Route::post('refresh', [\App\Http\Controllers\API\v1\AuthController::class,'refresh']);

    Route::get('me', [\App\Http\Controllers\API\v1\AuthController::class,'me']);

    Route::get('dashboard',[\App\Http\Controllers\API\v1\DashboardController::class,'index']);
});

Route::post('/verify-email-otp', [\App\Http\Controllers\API\v1\AuthController::class, 'verify_email']);

Route::post('orders/store', [\App\Http\Controllers\API\v1\OrderController::class,'store']);


Route::get('stores/all', [\App\Http\Controllers\API\v1\HomeController::class,'listStores']);
Route::get('stores/{id}',[\App\Http\Controllers\API\v1\HomeController::class,'getStore']);





