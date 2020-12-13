<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\Category as CategoryResource;
use Illuminate\Http\Request;

class Categories extends Controller
{
    /**
     * Get list of Categories
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(Request $request)
    {
        // Get Categories
        $categories = Category::recursiveCategories(Category::where('status', 'published')->whereNull('parent_id')->get());

        return CategoryResource::collection($categories);
    }
}
