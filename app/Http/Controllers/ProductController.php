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
     * New functionalities:
     * 1. Search
     * 2. Category filter
     * 3. Pin status filter
     * 4. Price range filter
     * 5. Sorting
     * 6. Pagination
     */
    public function index(Request $request)
    {
        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $search = $request->input('search');

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        $categoryId = $request->input('category_id');

        /*
        |--------------------------------------------------------------------------
        | Pin Status Filter
        |--------------------------------------------------------------------------
        */

        $pinStatus = $request->input('pin_status', 'all');

        /*
        |--------------------------------------------------------------------------
        | Price Filters
        |--------------------------------------------------------------------------
        */

        $minPrice = $request->input('min_price');

        $maxPrice = $request->input('max_price');

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'name',
            'price',
            'pin_priority',
            'created_at',
        ];

        $sort = $request->input('sort', 'id');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }

        $direction = strtolower($request->input('direction', 'asc'));

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        /*
        |--------------------------------------------------------------------------
        | Product Query
        |--------------------------------------------------------------------------
        */

        $query = Product::with('category');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('details', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        /*
        |--------------------------------------------------------------------------
        | Pin Status Filter
        |--------------------------------------------------------------------------
        */

        if ($pinStatus === 'active') {
            $query->where('is_pinned', 1)
                ->where(function ($q) use ($now) {
                    $q->whereNull('pin_start_at')
                        ->orWhere('pin_start_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('pin_end_at')
                        ->orWhere('pin_end_at', '>=', $now);
                });
        }

        if ($pinStatus === 'scheduled') {
            $query->where('is_pinned', 1)
                ->whereNotNull('pin_start_at')
                ->where('pin_start_at', '>', $now);
        }

        if ($pinStatus === 'expired') {
            $query->where('is_pinned', 1)
                ->whereNotNull('pin_end_at')
                ->where('pin_end_at', '<', $now);
        }

        if ($pinStatus === 'unpinned') {
            $query->where('is_pinned', 0);
        }

        if ($pinStatus === 'pinned') {
            $query->where('is_pinned', 1);
        }

        /*
        |--------------------------------------------------------------------------
        | Minimum Price
        |--------------------------------------------------------------------------
        */

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', $minPrice);
        }

        /*
        |--------------------------------------------------------------------------
        | Maximum Price
        |--------------------------------------------------------------------------
        */

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', $maxPrice);
        }

        /*
        |--------------------------------------------------------------------------
        | Active Pinned Products Always First
        |--------------------------------------------------------------------------
        */

        $query->orderByRaw("
            CASE
                WHEN is_pinned = 1
                AND (pin_start_at IS NULL OR pin_start_at <= ?)
                AND (pin_end_at IS NULL OR pin_end_at >= ?)
                THEN 0
                ELSE 1
            END
        ", [$now, $now]);

        /*
        |--------------------------------------------------------------------------
        | Pin Priority
        |--------------------------------------------------------------------------
        */

        $query->orderBy('pin_priority', 'ASC');

        /*
        |--------------------------------------------------------------------------
        | Selected Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy($sort, $direction);

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $products = $query
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Category::orderBy('name', 'ASC')->get();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();

        $totalPinned = Product::where('is_pinned', 1)->count();

        $totalUnpinned = Product::where('is_pinned', 0)->count();

        $activePinned = Product::where('is_pinned', 1)
            ->where(function ($q) use ($now) {
                $q->whereNull('pin_start_at')
                    ->orWhere('pin_start_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('pin_end_at')
                    ->orWhere('pin_end_at', '>=', $now);
            })
            ->count();

        return view('product.index', compact(
            'products',
            'categories',
            'search',
            'categoryId',
            'pinStatus',
            'minPrice',
            'maxPrice',
            'sort',
            'direction',
            'totalProducts',
            'totalPinned',
            'totalUnpinned',
            'activePinned'
        ));
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

        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' .
                $request->image->extension();

            $request->image->move(
                public_path('products'),
                $imageName
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Product
        |--------------------------------------------------------------------------
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

        /*
        |--------------------------------------------------------------------------
        | Replace Image
        |--------------------------------------------------------------------------
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
     * Delete single product.
     */
    public function delete($id)
    {
        $product = Product::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
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
            $product->update([
                'is_pinned' => 0,
                'pin_priority' => 0,
                'pin_start_at' => null,
                'pin_end_at' => null,
            ]);

            $message = 'Product unpinned successfully.';
        } else {
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
     * Bulk pin / unpin / delete.
     *
     * Functionality 7 and 8.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',

            'ids.*' => 'integer|exists:products,id',

            'action' => 'required|in:pin,unpin,delete',
        ]);

        $products = Product::whereIn('id', $request->ids)->get();

        /*
        |--------------------------------------------------------------------------
        | Bulk Pin
        |--------------------------------------------------------------------------
        */

        if ($request->action === 'pin') {
            foreach ($products as $product) {
                $product->update([
                    'is_pinned' => 1,
                    'pin_priority' => $product->pin_priority ?: 1,
                ]);
            }

            return redirect()
                ->back()
                ->with(
                    'success',
                    $products->count() . ' product(s) pinned successfully.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Bulk Unpin
        |--------------------------------------------------------------------------
        */

        if ($request->action === 'unpin') {
            foreach ($products as $product) {
                $product->update([
                    'is_pinned' => 0,
                    'pin_priority' => 0,
                    'pin_start_at' => null,
                    'pin_end_at' => null,
                ]);
            }

            return redirect()
                ->back()
                ->with(
                    'success',
                    $products->count() . ' product(s) unpinned successfully.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Bulk Delete
        |--------------------------------------------------------------------------
        */

        if ($request->action === 'delete') {
            foreach ($products as $product) {
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
            }

            return redirect()
                ->back()
                ->with(
                    'success',
                    $products->count() . ' product(s) deleted successfully.'
                );
        }

        return redirect()->back();
    }

    /**
     * Export products to CSV.
     *
     * Functionality 9.
     */
    public function exportCsv(Request $request)
    {
        $now = Carbon::now();

        $search = $request->input('search');

        $categoryId = $request->input('category_id');

        $pinStatus = $request->input('pin_status', 'all');

        $minPrice = $request->input('min_price');

        $maxPrice = $request->input('max_price');

        $allowedSorts = [
            'id',
            'name',
            'price',
            'pin_priority',
            'created_at',
        ];

        $sort = $request->input('sort', 'id');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }

        $direction = strtolower($request->input('direction', 'asc'));

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query = Product::with('category');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('details', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        /*
        |--------------------------------------------------------------------------
        | Pin Status
        |--------------------------------------------------------------------------
        */

        if ($pinStatus === 'active') {
            $query->where('is_pinned', 1)
                ->where(function ($q) use ($now) {
                    $q->whereNull('pin_start_at')
                        ->orWhere('pin_start_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('pin_end_at')
                        ->orWhere('pin_end_at', '>=', $now);
                });
        }

        if ($pinStatus === 'scheduled') {
            $query->where('is_pinned', 1)
                ->whereNotNull('pin_start_at')
                ->where('pin_start_at', '>', $now);
        }

        if ($pinStatus === 'expired') {
            $query->where('is_pinned', 1)
                ->whereNotNull('pin_end_at')
                ->where('pin_end_at', '<', $now);
        }

        if ($pinStatus === 'unpinned') {
            $query->where('is_pinned', 0);
        }

        if ($pinStatus === 'pinned') {
            $query->where('is_pinned', 1);
        }

        /*
        |--------------------------------------------------------------------------
        | Price Range
        |--------------------------------------------------------------------------
        */

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', $maxPrice);
        }

        /*
        |--------------------------------------------------------------------------
        | Ordering
        |--------------------------------------------------------------------------
        */

        $query->orderByRaw("
            CASE
                WHEN is_pinned = 1
                AND (pin_start_at IS NULL OR pin_start_at <= ?)
                AND (pin_end_at IS NULL OR pin_end_at >= ?)
                THEN 0
                ELSE 1
            END
        ", [$now, $now]);

        $query->orderBy('pin_priority', 'ASC');

        $query->orderBy($sort, $direction);

        $products = $query->get();

        /*
        |--------------------------------------------------------------------------
        | CSV Download
        |--------------------------------------------------------------------------
        */

        $fileName = 'products_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Name',
                'Category',
                'Price',
                'Details',
                'Pin Status',
                'Priority',
                'Pin Start',
                'Pin End',
                'Created At',
            ]);

            foreach ($products as $product) {
                $status = 'Unpinned';

                if ($product->isPinCurrentlyActive()) {
                    $status = 'Active';
                } elseif (
                    $product->is_pinned &&
                    $product->pin_start_at &&
                    $product->pin_start_at->gt(now())
                ) {
                    $status = 'Scheduled';
                } elseif (
                    $product->is_pinned &&
                    $product->pin_end_at &&
                    $product->pin_end_at->lt(now())
                ) {
                    $status = 'Expired';
                } elseif ($product->is_pinned) {
                    $status = 'Pinned';
                }

                fputcsv($handle, [
                    $product->id,
                    $product->name,
                    $product->category
                        ? $product->category->name
                        : '',
                    $product->price,
                    $product->details,
                    $status,
                    $product->pin_priority,
                    $product->pin_start_at
                        ? $product->pin_start_at->format('Y-m-d H:i:s')
                        : '',
                    $product->pin_end_at
                        ? $product->pin_end_at->format('Y-m-d H:i:s')
                        : '',
                    $product->created_at
                        ? $product->created_at->format('Y-m-d H:i:s')
                        : '',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Frontend product listing.
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

        $scheduledPinned = Product::where('is_pinned', 1)
            ->whereNotNull('pin_start_at')
            ->where('pin_start_at', '>', $now)
            ->count();

        $expiredPinned = Product::where('is_pinned', 1)
            ->whereNotNull('pin_end_at')
            ->where('pin_end_at', '<', $now)
            ->count();

        $pinPercentage = $totalProducts > 0
            ? round(($totalPinned / $totalProducts) * 100, 2)
            : 0;

        $topPinnedProducts = Product::with('category')
            ->where('is_pinned', 1)
            ->orderBy('pin_priority', 'ASC')
            ->orderBy('id', 'ASC')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Extra Statistics
        |--------------------------------------------------------------------------
        */

        $averagePrice = Product::avg('price');

        $highestPrice = Product::max('price');

        $lowestPrice = Product::min('price');

        $totalProductValue = Product::sum('price');

        $productsAddedToday = Product::whereDate(
            'created_at',
            today()
        )->count();

        $productsAddedThisWeek = Product::whereBetween(
            'created_at',
            [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]
        )->count();

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
                'topPinnedProducts',
                'averagePrice',
                'highestPrice',
                'lowestPrice',
                'totalProductValue',
                'productsAddedToday',
                'productsAddedThisWeek'
            )
        );
    }
}