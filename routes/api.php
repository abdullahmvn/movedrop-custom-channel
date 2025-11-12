<?php

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
    });

    Route::get('/orders', function (Request $request) {
        return response()->json([
            'orders' => [
                ['id' => 1, 'item' => 'Product A', 'quantity' => 2],
                ['id' => 2, 'item' => 'Product B', 'quantity' => 1],
            ],
        ]);
    });
});
