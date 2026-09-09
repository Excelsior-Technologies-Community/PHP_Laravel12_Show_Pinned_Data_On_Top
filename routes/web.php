<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


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

Route::get('/', [ProductController::class, 'frontendProducts'])
    ->name('frontend.products');

Route::get('/product-detail/{id}', [ProductController::class, 'show'])
    ->name('frontend.product.detail');


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
| Pin Statistics
|--------------------------------------------------------------------------
*/

Route::get('/product-statistics', [ProductController::class, 'pinStatistics'])
    ->name('product.statistics');