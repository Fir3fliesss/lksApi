<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CheckoutController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public auth routes
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('api.login');

    // Public product listing
    Route::apiResource('products', ProductController::class)
        ->only(['index', 'show'])
        ->names('api.products');

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

        // User profile
        Route::get('/me', [UserController::class, 'profile'])->name('api.me');

        // Cart
        Route::apiResource('cart-items', CartController::class)
            ->except(['show'])
            ->names('api.cart');

        // Checkout
        Route::post('/checkout', CheckoutController::class)->name('api.checkout');

        // Orders
        Route::apiResource('orders', OrderController::class)
            ->only(['index', 'show'])
            ->names('api.orders');
    });
});
