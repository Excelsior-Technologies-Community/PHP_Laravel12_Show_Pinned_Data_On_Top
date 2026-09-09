<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Show admin product list.
     *
     * Active pinned products appear first,
     * followed by inactive/normal products.
     */
    public function index()
    {
        $now = Carbon::now();

        $products = Product::with('category')
            ->orderByRaw("
                CASE
                    WHEN is_pinned = 1
                    AND (pin_start_at IS NULL OR pin_start_at <= ?)
                    AND (pin_end_at IS NULL OR pin_end_at >= ?)
                    THEN 0
                    ELSE 1
                END
            ", [$now, $now])
            ->orderBy('pin_priority', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        return view('product.index', compact('products'));
    }

    /**
     * Show add product form.
     */
    public function create()
    {
        $categories = Category::all();

        return view('product.create', compact('categories'));
    }

    /**
     * Store new product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'price' => 'required|numeric|min:0',

            'details' => 'nullable|string',

            'category_id' => 'required|exists:categories,id',

            'image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',

            'pin_priority' => 'nullable|integer|min:0',

            'pin_start_at' => 'nullable|date',

            'pin_end_at' => 'nullable|date|after_or_equal:pin_start_at',
        ]);

        $imageName = null;

        /**
         * Upload image.
         */
        if ($request->hasFile('image')) {

            $imageName = time() . '_' . uniqid() . '.' .
                $request->image->extension();

            $request->image->move(
                public_path('products'),
                $imageName
            );
        }

        /**
         * Create product.
         */
        Product::create([
            'name' => $request->name,

            'price' => $request->price,

            'details' => $request->details,

            'category_id' => $request->category_id,

            'image' => $imageName,

            'is_pinned' => $request->has('is_pinned') ? 1 : 0,

            'pin_priority' => $request->has('is_pinned')
                ? ($request->pin_priority ?? 0)
                : 0,

            'pin_start_at' => $request->has('is_pinned')
                ? $request->pin_start_at
                : null,

            'pin_end_at' => $request->has('is_pinned')
                ? $request->pin_end_at
                : null,
        ]);

        return redirect()
            ->route('product.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show edit product form.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $categories = Category::all();

        return view(
            'product.edit',
            compact('product', 'categories')
        );
    }

    /**
     * Update product.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',

            'price' => 'required|numeric|min:0',

            'details' => 'nullable|string',

            'category_id' => 'required|exists:categories,id',

            'image' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',

            'pin_priority' => 'nullable|integer|min:0',

            'pin_start_at' => 'nullable|date',

            'pin_end_at' => 'nullable|date|after_or_equal:pin_start_at',
        ]);

        $imageName = $product->image;

        /**
         * Replace product image.
         */
        if ($request->hasFile('image')) {

            if (
                $product->image &&
                File::exists(
                    public_path('products/' . $product->image)
                )
            ) {
                File::delete(
                    public_path('products/' . $product->image)
                );
            }

            $imageName = time() . '_' . uniqid() . '.' .
                $request->image->extension();

            $request->image->move(
                public_path('products'),
                $imageName
            );
        }

        $isPinned = $request->has('is_pinned');

        $product->update([
            'name' => $request->name,

            'price' => $request->price,

            'details' => $request->details,

            'category_id' => $request->category_id,

            'image' => $imageName,

            'is_pinned' => $isPinned ? 1 : 0,

            'pin_priority' => $isPinned
                ? ($request->pin_priority ?? 0)
                : 0,

            'pin_start_at' => $isPinned
                ? $request->pin_start_at
                : null,

            'pin_end_at' => $isPinned
                ? $request->pin_end_at
                : null,
        ]);

        return redirect()
            ->route('product.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete product.
     */
    public function delete($id)
    {
        $product = Product::findOrFail($id);

        /**
         * Delete image from public/products.
         */
        if (
            $product->image &&
            File::exists(
                public_path('products/' . $product->image)
            )
        ) {
            File::delete(
                public_path('products/' . $product->image)
            );
        }

        $product->delete();

        return redirect()
            ->back()
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle pin / unpin product.
     */
    public function pin($id)
    {
        $product = Product::findOrFail($id);

        if ($product->is_pinned) {

            /**
             * Unpin product.
             */
            $product->update([
                'is_pinned' => 0,
                'pin_priority' => 0,
                'pin_start_at' => null,
                'pin_end_at' => null,
            ]);

            $message = 'Product unpinned successfully.';
        } else {

            /**
             * Pin product.
             */
            $product->update([
                'is_pinned' => 1,
                'pin_priority' => $product->pin_priority ?: 1,
            ]);

            $message = 'Product pinned successfully.';
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * Frontend product listing.
     *
     * Only currently active pinned products appear first.
     */
    public function frontendProducts()
    {
        $now = Carbon::now();

        $products = Product::with('category')
            ->orderByRaw("
                CASE
                    WHEN is_pinned = 1
                    AND (pin_start_at IS NULL OR pin_start_at <= ?)
                    AND (pin_end_at IS NULL OR pin_end_at >= ?)
                    THEN 0
                    ELSE 1
                END
            ", [$now, $now])
            ->orderBy('pin_priority', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        return view(
            'frontend.products',
            compact('products')
        );
    }

    /**
     * Show frontend product detail.
     */
    public function show($id)
    {
        $product = Product::with('category')
            ->findOrFail($id);

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderBy('is_pinned', 'DESC')
            ->orderBy('pin_priority', 'ASC')
            ->get();

        return view(
            'frontend.product-detail',
            compact('product', 'relatedProducts')
        );
    }

    /**
     * Pin statistics dashboard.
     */
    public function pinStatistics()
    {
        $now = Carbon::now();

        $totalProducts = Product::count();

        $totalPinned = Product::where('is_pinned', 1)->count();

        $totalUnpinned = Product::where(
            'is_pinned',
            0
        )->count();

        /**
         * Currently active pinned products.
         */
        $activePinned = Product::where('is_pinned', 1)
            ->where(function ($query) use ($now) {
                $query->whereNull('pin_start_at')
                    ->orWhere('pin_start_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('pin_end_at')
                    ->orWhere('pin_end_at', '>=', $now);
            })
            ->count();

        /**
         * Scheduled products.
         */
        $scheduledPinned = Product::where('is_pinned', 1)
            ->whereNotNull('pin_start_at')
            ->where('pin_start_at', '>', $now)
            ->count();

        /**
         * Expired pinned products.
         */
        $expiredPinned = Product::where('is_pinned', 1)
            ->whereNotNull('pin_end_at')
            ->where('pin_end_at', '<', $now)
            ->count();

        /**
         * Pin percentage.
         */
        $pinPercentage = $totalProducts > 0
            ? round(($totalPinned / $totalProducts) * 100, 2)
            : 0;

        /**
         * Top priority products.
         */
        $topPinnedProducts = Product::with('category')
            ->where('is_pinned', 1)
            ->orderBy('pin_priority', 'ASC')
            ->limit(5)
            ->get();

        return view(
            'product.statistics',
            compact(
                'totalProducts',
                'totalPinned',
                'totalUnpinned',
                'activePinned',
                'scheduledPinned',
                'expiredPinned',
                'pinPercentage',
                'topPinnedProducts'
            )
        );
    }
}