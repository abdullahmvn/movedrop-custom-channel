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
}
