<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $product->delete();
        return redirect()->back();
    }
}

