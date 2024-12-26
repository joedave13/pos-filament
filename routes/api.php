<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShopSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('products', ProductController::class)->only(['index']);
    Route::get('products/get-product-by-barcode', [ProductController::class, 'getProductByBarcode']);

    Route::apiResource('payment-methods', PaymentMethodController::class)->only(['index']);

    Route::apiResource('orders', OrderController::class)->only(['index', 'store']);

    Route::apiResource('shop-settings', ShopSettingController::class)->only(['index']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
