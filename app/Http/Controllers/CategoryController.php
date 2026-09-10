<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Show category list.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name', 'ASC')
            ->get();

        return view(
            'categories.index',
            compact('categories')
        );
    }

    /**
     * Show add category form.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store new category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()
            ->route('categories.index')
            ->with(
                'success',
                'Category created successfully.'
            );
    }

    /**
     * Show edit category form.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view(
            'categories.edit',
            compact('category')
        );
    }

    /**
     * Update category.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()
            ->route('categories.index')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }

    /**
     * Delete category.
     */
    public function delete($id)
    {
        $category = Category::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Prevent deleting category with products
        |--------------------------------------------------------------------------
        */

        if ($category->products()->exists()) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'This category cannot be deleted because products are assigned to it.'
                );
        }

        $category->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }
}