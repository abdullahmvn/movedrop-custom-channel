<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        // Logic to store a new product
        $product = [
            'id' => 1,
            'title' => $request->input('title'),
        ];

        return response()->json([
            'message' => 'Product Created',
            'data' => $product,
        ], 201);
    }

    public function storeVariations(Request $request)
    {
        // Logic to store a new product
        $response = [
            [
                'id' => 2,
                'sku' => 'TEST-SKU-2',
            ],
            [
                'error' => [
                    'code' => 'product_duplicate_sku',
                    'message' => 'SKU already exists.',
                    'data' => [
                        'variation_id' => 1,
                        'sku' => 'TEST-SKU-1',
                    ],
                ],
            ],
        ];

        return response()->json([
            'message' => 'Product Variations Created',
            'data' => $response,
        ], 201);
    }
}
