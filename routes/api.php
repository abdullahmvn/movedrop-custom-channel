<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\AuthByApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(AuthByApiKey::class)->group(function () {
    // 1
    Route::post('/webhooks', [WebhookController::class, 'store']);

    // 2,3
    Route::resource('/categories', CategoryController::class)->only(['index', 'store']);

    Route::prefix('products')->group(function () {
        // 4
        Route::post('/', [ProductController::class, 'store']);
        // 5
        Route::post('/{id}/variations', [ProductController::class, 'storeVariations']);
        // 6
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    // 7
    Route::get('/orders', [OrderController::class, 'index']);
    // 8
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    // 9
    Route::post('/orders/{id}/timelines', [OrderController::class, 'storeTimeline']);
});
