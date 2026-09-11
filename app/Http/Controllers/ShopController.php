<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function products(Request $request)
    {
        $query = Product::with('category')->where('stock', '>=', 0);
        $search = trim((string) $request->input('search'));
        $categoryId = $request->input('category_id');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        $sort = $request->input('sort', 'featured');
        if ($sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price');
        } elseif ($sort === 'newest') {
            $query->latest();
        } else {
            $query->orderByDesc('is_pinned')->orderBy('pin_priority')->latest();
        }

        return view('frontend.products', [
            'products' => $query->paginate(12)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'filters' => $request->only(['search', 'category_id', 'min_price', 'max_price', 'sort']),
            'wishlistIds' => session('wishlist', []),
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'variants', 'reviews' => fn ($query) => $query->where('approved', true)])
            ->findOrFail($id);
        $product->increment('views');
        DB::table('product_impressions')->insert([
            'product_id' => $product->id,
            'session_id' => session()->getId(),
            'event' => 'view',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $recent = collect(session('recently_viewed', []))->prepend($product->id)->unique()->take(10)->values()->all();
        session(['recently_viewed' => $recent]);

        $relatedProducts = Product::with('category')->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)->latest()->limit(4)->get();
        $recentProducts = Product::whereIn('id', array_slice($recent, 1))->get()->sortBy(fn ($item) => array_search($item->id, $recent))->values();

        return view('frontend.product-detail', compact('product', 'relatedProducts', 'recentProducts'));
    }

    public function toggleWishlist($id)
    {
        Product::findOrFail($id);
        $wishlist = collect(session('wishlist', []));
        $wishlist->contains($id) ? $wishlist = $wishlist->reject(fn ($item) => (int) $item === (int) $id) : $wishlist->push((int) $id);
        session(['wishlist' => $wishlist->unique()->values()->all()]);
        return back()->with('success', $wishlist->contains($id) ? 'Product added to wishlist.' : 'Product removed from wishlist.');
    }

    public function wishlist()
    {
        $products = Product::whereIn('id', session('wishlist', []))->get();
        return view('frontend.wishlist', compact('products'));
    }

    public function toggleCompare($id)
    {
        Product::findOrFail($id);
        $compare = collect(session('compare', []));
        if ($compare->contains($id)) {
            $compare = $compare->reject(fn ($item) => (int) $item === (int) $id);
        } elseif ($compare->count() < 4) {
            $compare->push((int) $id);
        } else {
            return back()->with('error', 'You can compare up to 4 products.');
        }
        session(['compare' => $compare->unique()->values()->all()]);
        return back()->with('success', 'Compare list updated.');
    }

    public function compare()
    {
        $products = Product::whereIn('id', session('compare', []))->get();
        return view('frontend.compare', compact('products'));
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart = session('cart', []);
        $cart[$id] = min(($cart[$id] ?? 0) + $quantity, $product->stock ?: PHP_INT_MAX);
        session(['cart' => $cart]);
        return back()->with('success', 'Product added to cart.');
    }

    public function cart()
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->map(function ($product) use ($cart) {
            $product->cart_quantity = $cart[$product->id];
            return $product;
        });
        $subtotal = $products->sum(fn ($product) => $product->selling_price * $product->cart_quantity);
        return view('frontend.cart', compact('products', 'subtotal'));
    }

    public function removeFromCart($id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return back()->with('success', 'Product removed from cart.');
    }

    public function checkout()
    {
        abort_if(empty(session('cart', [])), 404, 'Your cart is empty.');
        return view('frontend.checkout');
    }

    public function orders()
    {
        $orders = Order::with('items')->where('session_id', session()->getId())->latest()->get();
        return view('frontend.orders', compact('orders'));
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required|string|max:30',
            'shipping_address' => 'required|string|max:1000',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:cod',
        ]);
        $cart = session('cart', []);
        abort_if(empty($cart), 404, 'Your cart is empty.');

        $products = Product::whereIn('id', array_keys($cart))->get();
        $subtotal = 0;
        foreach ($products as $product) {
            $quantity = (int) $cart[$product->id];
            abort_if($product->stock > 0 && $quantity > $product->stock, 422, "Insufficient stock for {$product->name}.");
            $subtotal += $product->selling_price * $quantity;
        }

        $order = DB::transaction(function () use ($data, $products, $cart, $subtotal) {
            $order = Order::create(array_merge($data, [
                'session_id' => session()->getId(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'subtotal' => $subtotal,
                'shipping_amount' => 0,
                'total' => $subtotal,
                'status' => 'pending',
            ]));
            DB::table('addresses')->insert([
                'session_id' => session()->getId(),
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone'],
                'address' => $data['shipping_address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'postal_code' => $data['postal_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            foreach ($products as $product) {
                $quantity = (int) $cart[$product->id];
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $product->selling_price,
                    'total' => $product->selling_price * $quantity,
                ]);
                if ($product->stock > 0) {
                    $product->decrement('stock', $quantity);
                }
            }
            return $order;
        });
        session()->forget('cart');
        return view('frontend.order-success', compact('order'));
    }

    public function review(Request $request, $id)
    {
        Product::findOrFail($id);
        $data = $request->validate(['name' => 'required|string|max:255', 'rating' => 'required|integer|min:1|max:5', 'comment' => 'nullable|string|max:2000']);
        Review::create(array_merge($data, ['product_id' => $id, 'approved' => true]));
        return back()->with('success', 'Review submitted successfully.');
    }
}
