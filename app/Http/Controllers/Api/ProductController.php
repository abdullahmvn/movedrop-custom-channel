<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductVariationStoreRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductMedia;
use App\Models\ProductProperty;
use App\Models\ProductPropertyValue;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function store(ProductStoreRequest $request): JsonResponse
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
            ], Response::HTTP_BAD_REQUEST);
        }

        // Logic to store a new product
        $product = DB::transaction(function () use ($payload) {
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

            return $product;
        });

        return response()->json([
            'message' => 'Product Created',
            'data' => new ProductResource($product),
        ], 201);
    }

    public function storeVariations(ProductVariationStoreRequest $request, $productId): JsonResponse
    {
        $product = Product::query()->findOrFail($productId);

        $variationsPayload = $request->validated()['variations'];

        $variationsResponses = DB::transaction(function () use ($product, $variationsPayload) {
            $variationsResponses = [];
            foreach ($variationsPayload as $item) {
                // Check for existing variation with the same SKU
                $oldVariation = $product->variations()->where('sku', $item['sku'])->first();
                if ($oldVariation) {
                    $variationsResponses[] = [
                        'error' => [
                            'code' => 'variation_duplicate_sku',
                            'message' => 'SKU already exists.',
                            'data' => [
                                'variation_id' => $oldVariation->id,
                                'sku' => $oldVariation->sku,
                            ],
                        ],
                    ];
                    continue;
                }

                // Logic to store a new variation
                $variation = $product->variations()->create([
                    'sku' => $item['sku'],
                    'regular_price' => $item['regular_price'],
                    'sale_price' => $item['sale_price'] ?? null,
                    'date_on_sale_from' => isset($item['date_on_sale_from']) ? Carbon::parse($item['date_on_sale_from']) : null,
                    'date_on_sale_to' => isset($item['date_on_sale_to']) ? Carbon::parse($item['date_on_sale_to']) : null,
                    'stock_quantity' => $item['stock_quantity'],
                    'image' => $item['image'],
                ]);

                // Attach properties to variation
                foreach ($item['properties'] as $property) {
                    $propertyRecord = ProductProperty::query()
                        ->where('product_id', $product->id)
                        ->where('name', $property['name'])
                        ->first();

                    if ($propertyRecord) {
                        $valueRecord = ProductPropertyValue::query()
                            ->where('product_property_id', $propertyRecord->id)
                            ->where('value', $property['value'])
                            ->first();

                        if ($valueRecord) {
                            $variation->properties()->create([
                                'product_property_id' => $propertyRecord->id,
                                'product_property_value_id' => $valueRecord->id,
                            ]);
                        }
                    }
                }

                $variationsResponses[] = [
                    'id' => $variation->id,
                    'sku' => $variation->sku,
                ];
            }

            return $variationsResponses;
        });

        return response()->json([
            'message' => 'Product Variations Created',
            'data' => $variationsResponses,
        ], 201);
    }
}
