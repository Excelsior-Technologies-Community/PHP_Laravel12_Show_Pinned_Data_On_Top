<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show admin product list (Pinned products first)
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('is_pinned', 'DESC') // Pinned products on top
            ->orderBy('id', 'ASC')
            ->get();

        return view('product.index', compact('products'));
    }

    // Show add product form
    public function create()
    {
        $categories = Category::all();
        return view('product.create', compact('categories'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'price'       => 'required',
            'category_id' => 'required',
            'image'       => 'image|mimes:jpg,png,jpeg,webp'
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('products'), $imageName);
        }

        Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'details'     => $request->details,
            'category_id' => $request->category_id,
            'image'       => $imageName,
            'is_pinned'   => 0 // default not pinned
        ]);

        return redirect()->route('product.index');
    }

    // Show edit product form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('product.edit', compact('product', 'categories'));
    }

    // Update product details
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $imageName = $product->image;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('products'), $imageName);
        }

        $product->update([
            'name'        => $request->name,
            'price'       => $request->price,
            'details'     => $request->details,
            'category_id' => $request->category_id,
            'image'       => $imageName
        ]);

        return redirect()->route('product.index');
    }

    // Delete product
    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->back();
    }

    // Toggle pin / unpin product
    public function pin($id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'is_pinned' => !$product->is_pinned
        ]);

        return redirect()->back();
    }

    // Show frontend product listing (Pinned products first)
    public function frontendProducts()
    {
        $products = Product::with('category')
            ->orderBy('is_pinned', 'DESC')
            ->orderBy('id', 'ASC')
            ->get();

        return view('frontend.products', compact('products'));
    }

    // Show frontend product detail page
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->get();

        return view('frontend.product-detail', compact('product', 'relatedProducts'));
    }
}
