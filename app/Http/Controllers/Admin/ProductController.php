<?php

namespace App\Http\Controllers\Admin;

use App\Enums\WebhookEventEnum;
use App\Http\Controllers\Controller;
use App\Jobs\WebhookNotifierJob;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate();

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load([
            'categories',
            'media',
            'properties.propertyValues',
            'variations',
            'variations.properties.property',
            'variations.properties.propertyValue',
        ]);

        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        $id = $product->id;
        $product->delete();

        // Fire event to notify using webhooks
        WebhookNotifierJob::dispatch(WebhookEventEnum::PRODUCT_DELETED->value, [
            'id' => $id,
        ]);

        return redirect()->back();
    }
}

