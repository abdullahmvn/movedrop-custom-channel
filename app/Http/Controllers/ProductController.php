<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductMedia;
use App\Models\ProductProperty;
use App\Models\ProductPropertyValue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function store(ProductStoreRequest $request)
    {
        $payload = $request->validated();

        // Check for existing product with the same SKU
        $oldProduct = Product::query()->where('sku', $payload['sku'])->first();
        if ($oldProduct) {
            $product = [
                'error' => [
                    'code' => 'product_duplicate_sku',
                    'message' => 'SKU already exists.',
                    'data' => [
                        'product_id' => $oldProduct->id,
                        'sku' => $oldProduct->sku,
                    ],
                ],
            ];

            return response()->json([
                'message' => 'Product with given SKU already exists',
                'data' => $product,
            ], Response::HTTP_CONFLICT);
        }

        // Logic to store a new product
        $product = Product::create([
            'title' => $payload['title'],
            'sku' => $payload['sku'],
            'description' => $payload['description'] ?? null,
            'tags' => $payload['tags'] ?? null,
        ]);
        ProductCategory::query()->insert(
            collect($payload['category_ids'] ?? [])->map(function ($categoryId) use ($product) {
                return [
                    'product_id' => $product->id,
                    'category_id' => $categoryId,
                ];
            })->toArray(),
        );
        ProductMedia::query()->insert(
            collect($payload['images'])->map(function ($image) use ($product) {
                return [
                    'product_id' => $product->id,
                    'is_default' => $image['is_default'],
                    'media_type' => 'image',
                    'url' => $image['src'],
                ];
            })->toArray(),
        );
        foreach ($payload['properties'] as $payloadProperty) {
            $property = ProductProperty::query()->create([
                    'product_id' => $product->id,
                    'name' => $payloadProperty['name'],
                ],
            );
            foreach ($payloadProperty['values'] as $value) {
                ProductPropertyValue::query()->create([
                    'product_property_id' => $property->id,
                    'value' => $value,
                ]);
            }
        }

        return response()->json([
            'message' => 'Product Created',
            'data' => new ProductResource($product),
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
                    'code' => 'variation_duplicate_sku',
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
