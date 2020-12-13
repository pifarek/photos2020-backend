<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Collection;

class IndexController extends Controller
{
    /**
     * List of categories
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')->get();

        return view('categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        $categories = Category::recursiveCategoriesArray(Category::whereNull('parent_id')->orderBy('name', 'asc')->get());

        return view('categories.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:published,pending']
        ]);

        $category = new Category();
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->parent_id = $request->input('parent_id');
        $category->status = $request->input('status');
        $category->save();

        return redirect()->route('categories.index', [$category->id])->with('success', 'Category has been created successfully.');
    }

    /**
     * Edit selected category
     * @param int $category_id
     */
    public function edit(int $category_id)
    {
        $category = Category::find($category_id);

        if(!$category) {
            return redirect()->route('categories.index')->with('error', 'Selected Category has been not found.');
        }

        $categories = Category::recursiveCategoriesArray(Category::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name', 'asc')->get(), 0, $category_id);

        return view('categories.edit', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    /**
     * Update selected category
     * @param Request $request
     * @param int $category_id
     */
    public function update(Request $request, int $category_id)
    {
        $category = Category::find($category_id);

        if(!$category) {
            return redirect()->route('categories.index')->with('error', 'Selected Category has been not found.');
        }

        $request->validate([
            'name' => ['required'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:published,pending'],
        ]);

        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->parent_id = $request->input('parent_id');
        $category->status = $request->input('status');
        $category->save();

        return redirect()->route('categories.index', [$category->id])->with('success', 'Category has been updated successfully.');
    }

    public function remove(int $category_id)
    {
        $category = Category::find($category_id);
        if($category) {
            $category->delete();
        }

        return response()->json(['status' => 'ok']);
    }
}
