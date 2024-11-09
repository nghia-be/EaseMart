<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;  
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomersController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\CategoriesParentController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StockTransactionController;

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
// 

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::namespace('Api')->group(function () {
    Route::post('/log-report', 'LogReportController@saveReport');
    Route::post('/products', 'Api\ProductController@store')->name('products.store');
});

Route::namespace('Api')->group(function () {
    Route::post('/contract/get-info-by-customer-id', 'ContractController@show')->name('contract.show');
});
Route::namespace('CategoriesParent')->group(function(){
    Route::get('/categories-parents', [CategoriesParentController::class,'getCategoryParents']);
    Route::get('/categories-list', [CategoriesController::class, 'index']);
});
Route::namespace('Comments')->group(function(){
    Route::get('/comments-list', [CommentController::class, 'getComments']);
    Route::post('/comments', [CommentController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->middleware('auth:sanctum');
});
Route::namespace('Customers')->group(function(){
    Route::post('/register', [CustomersController::class, 'register']);
    Route::post('/login', [CustomersController::class, 'login']);
    Route::get('/customers-list', [CustomersController::class, 'getCustomers']);
    Route::post('/logout', [CustomersController::class, 'logout'])->middleware('auth:sanctum');
    Route::put('/customers/profile', [CustomersController::class, 'update'])->middleware('auth:sanctum');
    Route::put('/customers/update-password', [CustomersController::class, 'updatePassword'])->middleware('auth:sanctum');
    Route::post('/password/forgot', [CustomersController::class, 'sendOtp']);
    Route::post('/password/verify-otp', [CustomersController::class, 'verifyOtp']);
    Route::post('/password/reset', [CustomersController::class, 'resetPassword']);
    // Route::post('/forgot-password', [CustomersController::class, 'forgotPassword']);
    

});
Route::namespace('Order')->group(function(){
Route::post('/order',[OrderController::class, 'createOrder'])->middleware('auth:sanctum');
Route::get('/test',[OrderController::class, 'test'])->middleware('auth:sanctum');
});

Route::get('/location', [LocationController::class, 'getCurrentLocation']);

Route::get('/products-by-warehouse/{warehouse_code}', [StockTransactionController::class, 'getProductsByWarehouse']);
Route::get('/brand-list', [BrandController::class, 'getBrand']);
Route::get('/product/detail/{id}', [ProductController::class, 'getProductDetail']);
Route::get('/products',[ProductController::class,'getAll']);

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook']);
Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback']);


Route::post('/payment',[PaymentController::class,'get']);
Route::get('/payment-method',[PaymentController::class,'paymentMethod']);

   


