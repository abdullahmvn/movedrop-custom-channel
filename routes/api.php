<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AuthByApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(AuthByApiKey::class)->group(function () {
    Route::get('/orders', function (Request $request) {
        return response()->json([
            'orders' => [
                ['id' => 1, 'item' => 'Product A', 'quantity' => 2],
                ['id' => 2, 'item' => 'Product B', 'quantity' => 1],
            ],
        ]);
    });

    // 1
    Route::post('/webhooks', function (Request $request) {
        logger($request->all());
        return response()->json([
            'message' => 'Webhook Stored',
            'data' => $request->all(),
        ]);
    });

    // 2,3
    Route::resource('/categories', CategoryController::class)->only(['index', 'store']);

    // 4
    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::post('/{id}/variations', [ProductController::class, 'storeVariations']);
    });
});
