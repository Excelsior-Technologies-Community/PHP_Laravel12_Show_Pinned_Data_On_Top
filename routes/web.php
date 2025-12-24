<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/

// Show category list
Route::get('/categories', [CategoryController::class, 'index'])
    ->name('categories.index');

// Show add category form
Route::get('/categories/create', [CategoryController::class, 'create'])
    ->name('categories.create');

// Store category
Route::post('/categories/store', [CategoryController::class, 'store'])
    ->name('categories.store');

// Show edit category form
Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])
    ->name('categories.edit');

// Update category
Route::post('/categories/update/{id}', [CategoryController::class, 'update'])
    ->name('categories.update');

// Delete category
Route::get('/categories/delete/{id}', [CategoryController::class, 'delete'])
    ->name('categories.delete');

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

// Admin product list
Route::get('/product', [ProductController::class, 'index'])
    ->name('product.index');

// Show add product form
Route::get('/product/create', [ProductController::class, 'create'])
    ->name('product.create');

// Store product
Route::post('/product/store', [ProductController::class, 'store'])
    ->name('product.store');

// Show edit product form
Route::get('/product/edit/{id}', [ProductController::class, 'edit'])
    ->name('product.edit');

// Update product
Route::post('/product/update/{id}', [ProductController::class, 'update'])
    ->name('product.update');

// Delete product
Route::get('/product/delete/{id}', [ProductController::class, 'delete'])
    ->name('product.delete');

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// Frontend product listing
Route::get('/', [ProductController::class, 'frontendProducts'])
    ->name('frontend.products');

// Frontend product detail page
Route::get('/product-detail/{id}', [ProductController::class, 'show'])
    ->name('frontend.product.detail');


Route::get('/product/pin/{id}', [ProductController::class, 'pin'])
    ->name('product.pin');
    