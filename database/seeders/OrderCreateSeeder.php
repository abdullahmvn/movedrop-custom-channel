<?php

namespace Database\Seeders;

use App\Enums\WebhookEventEnum;
use App\Jobs\WebhookNotifierJob;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OrderCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lineItems = [];
        $products = Product::query()->inRandomOrder()->take(random_int(1, 3))->with('variations')->get();
        foreach ($products as $product) {
            $variationsPayload = [];
            $totalPrice = 0;
            $totalQ = 0;
            $variations = $product->variations()->inRandomOrder()->take(random_int(1, 3))->get();
            foreach ($variations as $variation) {
                $q = random_int(1, 5);
                $price = $variation->sale_price ?? $variation->regular_price;
                $totalPrice += $price * $q;
                $totalQ += $q;
                $variationsPayload[] = [
                    'variation_id' => $variation->id,
                    'sku' => $variation->sku,
                    'quantity' => $q,
                    'price' => $price,
                ];
            }

            $lineItems[] = [
                'product_id' => $product->id,
                'name' => $product->title,
                'quantity' => $totalQ,
                'total' => $totalPrice,
                'variations' => $variationsPayload
            ];
        }
        $orderPayload = [
            'order_number' => Str::random(10),
            'status' => 'pending',
            'currency' => 'USD',
            'total' => array_sum(array_column($lineItems, 'total')),
            'payment_method' => Arr::random(['cod', 'paypal', 'stripe']),
            'customer_note' => fake()->sentence,
            'shipping_address' => [
                'first_name' => fake()->firstName,
                'last_name' => fake()->lastName,
                'email' => fake()->safeEmail,
                'phone' => fake()->phoneNumber,
                'address_1' => fake()->streetAddress,
                'address_2' => fake()->secondaryAddress,
                'city' => fake()->city,
                'state' => fake()->state,
                'postal_code' => fake()->postcode,
                'country' => fake()->country,
            ],
        ];
        // Create Order
        $order = Order::query()->create([
            'order_number' => $orderPayload['order_number'],
            'status' => $orderPayload['status'],
            'currency' => $orderPayload['currency'],
            'total' => $orderPayload['total'],
            'payment_method' => $orderPayload['payment_method'],
            'customer_note' => $orderPayload['customer_note'],
            'shipping_address' => $orderPayload['shipping_address'],
        ]);
        // Create Order Items
        foreach ($lineItems as &$itemPayload) {
            $orderItem = $order->orderItems()->create([
                'product_id' => $itemPayload['product_id'],
                'name' => $itemPayload['name'],
                'quantity' => $itemPayload['quantity'],
                'total' => $itemPayload['total'],
            ]);
            $itemPayload['id'] = $orderItem->id;

            // Create Order Item Variations
            foreach ($itemPayload['variations'] as &$variationPayload) {
                $orderItemVariation = $orderItem->variations()->create([
                    'product_variation_id' => $variationPayload['variation_id'],
                    'sku' => $variationPayload['sku'],
                    'quantity' => $variationPayload['quantity'],
                    'price' => $variationPayload['price'],
                ]);
                $variationPayload['id'] = $orderItemVariation->id;
            }
        }


        $orderPayload['id'] = $order->id;
        $orderPayload['created_at'] = $order->created_at->toDateTimeString();
        $orderPayload['line_items'] = $lineItems;

        WebhookNotifierJob::dispatch(WebhookEventEnum::ORDER_CREATED->value, $orderPayload);
    }
}
