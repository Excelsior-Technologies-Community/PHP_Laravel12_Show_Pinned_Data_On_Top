<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;


/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/

Route::get('/categories', [CategoryController::class, 'index'])
    ->name('categories.index');

Route::get('/categories/create', [CategoryController::class, 'create'])
    ->name('categories.create');

Route::post('/categories/store', [CategoryController::class, 'store'])
    ->name('categories.store');

Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])
    ->name('categories.edit');

Route::post('/categories/update/{id}', [CategoryController::class, 'update'])
    ->name('categories.update');

Route::get('/categories/delete/{id}', [CategoryController::class, 'delete'])
    ->name('categories.delete');


/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ShopController::class, 'products'])
    ->name('frontend.products');

Route::get('/product-detail/{id}', [ShopController::class, 'show'])
    ->name('frontend.product.detail');

Route::get('/shop', [ShopController::class, 'products'])->name('shop.products');
Route::post('/wishlist/{id}/toggle', [ShopController::class, 'toggleWishlist'])->name('wishlist.toggle');
Route::get('/wishlist', [ShopController::class, 'wishlist'])->name('wishlist.index');
Route::post('/compare/{id}/toggle', [ShopController::class, 'toggleCompare'])->name('compare.toggle');
Route::get('/compare', [ShopController::class, 'compare'])->name('compare.index');
Route::post('/cart/{id}', [ShopController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [ShopController::class, 'cart'])->name('cart.index');
Route::delete('/cart/{id}', [ShopController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [ShopController::class, 'placeOrder'])->name('checkout.place');
Route::get('/orders', [ShopController::class, 'orders'])->name('orders.index');
Route::post('/product-detail/{id}/review', [ShopController::class, 'review'])->name('review.store');
Route::view('/contact', 'frontend.static', ['title' => 'Contact Us', 'content' => '<p>Email: support@example.com</p><p>Phone: +91 00000 00000</p>'])->name('contact');
Route::view('/about', 'frontend.static', ['title' => 'About Us', 'content' => '<p>Welcome to our product catalogue.</p>'])->name('about');
Route::view('/faq', 'frontend.static', ['title' => 'FAQ', 'content' => '<h3>How do I order?</h3><p>Add a product to cart and complete checkout.</p><h3>What payment methods are available?</h3><p>Cash on Delivery is currently available.</p>'])->name('faq');


/*
|--------------------------------------------------------------------------
| Admin Product Routes
|--------------------------------------------------------------------------
*/

Route::get('/product', [ProductController::class, 'index'])
    ->name('product.index');

Route::get('/product/create', [ProductController::class, 'create'])
    ->name('product.create');

Route::post('/product/store', [ProductController::class, 'store'])
    ->name('product.store');

Route::get('/product/edit/{id}', [ProductController::class, 'edit'])
    ->name('product.edit');

Route::post('/product/update/{id}', [ProductController::class, 'update'])
    ->name('product.update');

Route::get('/product/delete/{id}', [ProductController::class, 'delete'])
    ->name('product.delete');

Route::get('/product/pin/{id}', [ProductController::class, 'pin'])
    ->name('product.pin');


/*
|--------------------------------------------------------------------------
| Bulk Product Actions
|--------------------------------------------------------------------------
*/

Route::post('/product/bulk-action', [ProductController::class, 'bulkAction'])
    ->name('product.bulk-action');


/*
|--------------------------------------------------------------------------
| Product CSV Export
|--------------------------------------------------------------------------
*/

Route::get('/product/export-csv', [ProductController::class, 'exportCsv'])
    ->name('product.export-csv');
Route::post('/product/import-csv', [ProductController::class, 'importCsv'])
    ->name('product.import-csv');
Route::post('/product/reorder-pins', [ProductController::class, 'reorderPins'])
    ->name('product.reorder-pins');


/*
|--------------------------------------------------------------------------
| Pin Statistics
|--------------------------------------------------------------------------
*/

Route::get('/product-statistics', [ProductController::class, 'pinStatistics'])
    ->name('product.statistics');