<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with([
                'orderItems.variations',
            ])
            ->paginate(
                perPage: $request->query('per_page', 20),
                page: $request->query('page', 1),
            );

        return OrderResource::collection($orders);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        $order = Order::query()->findOrFail($id);
        $order->update($validated);

        return new OrderResource($order);
    }

    public function storeTimeline(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $order = Order::query()->findOrFail($id);
        $order->timelines()->create($validated);

        return new OrderResource($order);
    }
}
