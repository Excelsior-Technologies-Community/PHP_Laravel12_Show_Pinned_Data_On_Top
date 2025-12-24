<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Show category list
    public function index()
    {
        // Fetch all categories (latest first)
        $categories = Category::latest()->get();

        return view('categories.index', compact('categories'));
    }

    // Show add category form
    public function create()
    {
        return view('categories.create');
    }

    // Store new category
    public function store(Request $request)
    {
        // Validate category name
        $request->validate(['name' => 'required']);

        // Save category to database
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('categories.index');
    }

    // Show edit category form
    public function edit($id)
    {
        // Get category by id
        $category = Category::findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    // Update category
    public function update(Request $request, $id)
    {
        // Get category by id
        $category = Category::findOrFail($id);

        // Update category data
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('categories.index');
    }

    // Delete category
    public function delete($id)
    {
        // Remove category by id
        Category::findOrFail($id)->delete();

        return redirect()->back();
    }
}
