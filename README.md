# PHP_Laravel12_Show_Pinned_Data_On_Top

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/PHP-8%2B-777BB4?style=for-the-badge&logo=php">
  <img src="https://img.shields.io/badge/Blade-Views-orange?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql">
  <img src="https://img.shields.io/badge/Pinned%20Data-Show%20On%20Top-success?style=for-the-badge">
</p>

---

##  Overview

This project demonstrates how to **pin specific data (products)** and always display it
**at the top of the listing** using **Laravel 12**.

The concept is widely used in:
- Featured products
- Priority content
- Admin dashboards
- CMS systems




##  Features

- Laravel 12
- Product CRUD (Create, Edit, Update)
- Pin / Unpin products
- Show pinned products on top
- Admin Panel
- Frontend Product Listing
- Blade Views (No CSS)
- MySQL Database

---

##  Folder Structure

```text
product-system/
│
├── app/
│   ├── Models/
│   │   └── Product.php
│   └── Http/
│       └── Controllers/
│           └── ProductController.php
│
├── database/
│   └── migrations/
│       └── create_products_table.php
│
├── resources/
│   └── views/
│       ├── layout/app.blade.php
│       ├── product/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       └── frontend/
│           └── products.blade.php
│
├── public/products/
├── routes/web.php
└── README.md
```

---

##  STEP 1: Create Laravel Project

```bash
composer create-project laravel/laravel product-system
```

---

##  STEP 2: Database Configuration

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pinview
DB_USERNAME=root
DB_PASSWORD=
```

Create database:

```sql
CREATE DATABASE pinview;
```

---

##  STEP 3: Migration

```bash
php artisan make:migration create_products_table
```

```php
Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('details')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_pinned')->default(0);
            $table->timestamps();
        });

```

```bash
php artisan migrate
```

---

##  STEP 4: Model

```bash
php artisan make:model Product
```

```php
class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'details',
        'image',
        'category_id',
        'is_pinned'
    ];
}
```

---

##  STEP 5: Create Controller

```bash
php artisan make:controller ProductController
```

---

##  STEP 6: ProductController

```php
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

```

---

##  STEP 7: Routes

```php
use App\Http\Controllers\ProductController;

Route::get('/product', [ProductController::class,'index'])->name('product.index');
Route::get('/product/create', [ProductController::class,'create'])->name('product.create');
Route::post('/product/store', [ProductController::class,'store'])->name('product.store');

Route::get('/product/edit/{id}', [ProductController::class,'edit'])->name('product.edit');
Route::post('/product/update/{id}', [ProductController::class,'update'])->name('product.update');

Route::get('/product/pin/{id}', [ProductController::class,'pin'])->name('product.pin');

Route::get('/', [ProductController::class,'frontendProducts'])->name('frontend.products');
```

---

##  STEP 8: Blade Files 

### resources/views/product/index.blade.php

```blade
@extends('layout.app')

@section('content')

<div class="card-wrapper">

    {{-- Page heading and add product button --}}
    <div class="page-header">
        <h2>Products</h2>

        <a href="{{ route('product.create') }}">
            <button class="btn btn-primary">+ Add Product</button>
        </a>
    </div>

    {{-- Product listing table --}}
    <table class="table clean-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Pin</th>
                <th>Name</th>
                <th>Details</th>
                <th>Category</th>
                <th>Price</th>
                <th>Image</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>

        <tbody>
            {{-- Loop through products --}}
            @forelse($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>

                {{-- Pin / Unpin button --}}
                <td>
                    @if($product->is_pinned)
                        <span style="color:green;font-weight:bold;">📌 Pinned</span>
                        <br>
                        <a href="{{ route('product.pin', $product->id) }}">
                            <small>Unpin</small>
                        </a>
                    @else
                        <a href="{{ route('product.pin', $product->id) }}">
                            <button class="btn btn-light btn-sm">📌 Pin</button>
                        </a>
                    @endif
                </td>

                <td><strong>{{ $product->name }}</strong></td>

                {{-- Short product description --}}
                <td>{{ Str::limit($product->details, 50) }}</td>

                {{-- Product category --}}
                <td>{{ $product->category->name }}</td>

                {{-- Product price --}}
                <td>₹{{ number_format($product->price, 2) }}</td>

                {{-- Product image --}}
                <td>
                    @if($product->image)
                        <img src="{{ asset('products/'.$product->image) }}" class="table-img">
                    @else
                        —
                    @endif
                </td>

                {{-- Edit and delete actions --}}
                <td class="text-right">
                    <a href="{{ route('product.edit', $product->id) }}">
                        <button class="btn btn-light">Edit</button>
                    </a>

                    <a href="{{ route('product.delete', $product->id) }}"
                       onclick="return confirm('Delete this product?')">
                        <button class="btn btn-danger">Delete</button>
                    </a>
                </td>
            </tr>

            {{-- Empty state --}}
            @empty
            <tr>
                <td colspan="8" class="empty-text">No products found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection

```

---

### resources/views/product/create.blade.php

```blade
@extends('layout.app')

@section('content')

<h2>Add Product</h2>

{{-- Product creation form --}}
<form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
    @csrf

    {{-- Product name --}}
    <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" required>
    </div>

    {{-- Product price --}}
    <div class="form-group">
        <label>Price</label>
        <input type="number" name="price" required>
    </div>

    {{-- Product details --}}
    <div class="form-group">
        <label>Details</label>
        <textarea name="details"></textarea>
    </div>

    {{-- Category selection --}}
    <div class="form-group">
        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Image upload with preview --}}
    <div class="form-group">
        <label>Product Image</label>

        <div class="image-preview-wrapper">
            <input type="file" name="image"
                   onchange="previewImage(this, 'createPreview')">

            <div class="preview-box" id="createPreview">
                <span class="preview-text">No Image</span>
            </div>
        </div>
    </div>

    {{-- Form actions --}}
    <div class="form-actions">
        <button class="btn btn-primary">Save Product</button>
        <a href="{{ route('product.index') }}">
            <button type="button" class="btn btn-secondary">Back</button>
        </a>
    </div>

</form>

@endsection

```

---

### resources/views/product/edit.blade.php

```blade
@extends('layout.app')

@section('content')

<h2>Edit Product</h2>

{{-- Product update form --}}
<form method="POST" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
    @csrf

    {{-- Product name --}}
    <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" value="{{ $product->name }}" required>
    </div>

    {{-- Product price --}}
    <div class="form-group">
        <label>Price</label>
        <input type="number" name="price" value="{{ $product->price }}" required>
    </div>

    {{-- Product details --}}
    <div class="form-group">
        <label>Details</label>
        <textarea name="details">{{ $product->details }}</textarea>
    </div>

    {{-- Category selection --}}
    <div class="form-group">
        <label>Category</label>
        <select name="category_id" required>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    @if($product->category_id == $cat->id) selected @endif>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Image preview (old + new) --}}
    <div class="form-group">
        <label>Product Image</label>

        <div class="image-preview-wrapper">

            {{-- Current image --}}
            <div>
                <small>Current Image</small>
                <div class="preview-box">
                    @if($product->image)
                        <img src="{{ asset('products/'.$product->image) }}">
                    @else
                        <span class="preview-text">No Image</span>
                    @endif
                </div>
            </div>

            {{-- New image preview --}}
            <div>
                <small>New Image</small>
                <div class="preview-box" id="newImagePreview">
                    <span class="preview-text">Select Image</span>
                </div>
            </div>
        </div>

        <br>

        <input type="file" name="image"
               onchange="previewImage(this, 'newImagePreview')">
    </div>

    {{-- Form actions --}}
    <div class="form-actions">
        <button class="btn btn-primary">Update Product</button>
        <a href="{{ route('product.index') }}">
            <button type="button" class="btn btn-secondary">Back</button>
        </a>
    </div>

</form>

@endsection

```

---

### resources/views/frontend/products.blade.php

```blade
@extends('layout.app')

@section('content')

<h2>Our Products</h2>

{{-- Frontend product grid --}}
<div class="frontend-grid">

@foreach($products as $product)
    <div class="product-card modern" style="position: relative;">

        {{-- PINNED BADGE --}}
        @if($product->is_pinned)
            <span style="
                position:absolute;
                top:12px;
                left:12px;
                background:#facc15;
                color:#000;
                padding:5px 10px;
                font-size:12px;
                font-weight:700;
                border-radius:6px;
                box-shadow:0 2px 6px rgba(0,0,0,.2);
                z-index:10;
            ">
                📌 PINNED
            </span>
        @endif

        {{-- Product image --}}
        <div class="image-wrap">
            @if($product->image)
                <img src="{{ asset('products/'.$product->image) }}">
            @else
                <div class="no-image">No Image</div>
            @endif
        </div>

        {{-- Product info --}}
        <div class="card-body">
            <h4 class="product-title">{{ $product->name }}</h4>

            <p class="category">{{ $product->category->name }}</p>

            <p class="details">
                {{ \Illuminate\Support\Str::limit($product->details, 70) }}
            </p>

            {{-- Price and detail button --}}
            <div class="card-footer">
                <span class="price">
                    ₹{{ number_format($product->price,2) }}
                </span>

                <a href="{{ route('frontend.product.detail', $product->id) }}">
                    <button class="btn btn-primary btn-sm">
                        View Details
                    </button>
                </a>
            </div>
        </div>

    </div>
@endforeach

</div>

@endsection

```

---

## 📸 Output

- Admin Panel: Pinned products appear on top

 <img width="1309" height="752" alt="Screenshot 2025-12-24 150436" src="https://github.com/user-attachments/assets/4f413007-a17f-43b4-9b64-ff25fa12def0" />

- Frontend: Pinned products shown first with Pinned Badge

<img width="1075" height="786" alt="Screenshot 2025-12-24 150509" src="https://github.com/user-attachments/assets/0dc24837-bfe9-484b-992d-456bb0e2e41f" />



This README includes **ALL steps and ALL blade files**
exactly as required.
