<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()->paginate(
            perPage: $request->query('per_page', 20),
            page: $request->query('page', 1)
        );

        return CategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        $category = Category::query()->create([
            'name' => $request->input('name'),
            'slug' => str()->slug($request->input('name')),
        ]);

        return new CategoryResource($category);
    }
}
