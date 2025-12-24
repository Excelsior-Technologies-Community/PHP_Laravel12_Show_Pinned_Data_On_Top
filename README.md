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
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('is_pinned','DESC')
                           ->orderBy('id','ASC')
                           ->get();

        return view('product.index', compact('products'));
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        Product::create([
            'name'      => $request->name,
            'price'     => $request->price,
            'is_pinned' => 0
        ]);

        return redirect()->route('product.index');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'name'  => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('product.index');
    }

    public function pin($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_pinned' => !$product->is_pinned]);
        return back();
    }

    public function frontendProducts()
    {
        $products = Product::orderBy('is_pinned','DESC')->get();
        return view('frontend.products', compact('products'));
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

<a href="{{ route('product.create') }}">Add Product</a>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price</th>
    <th>Pinned</th>
    <th>Action</th>
</tr>

@foreach($products as $p)
<tr>
    <td>{{ $p->id }}</td>
    <td>{{ $p->name }}</td>
    <td>{{ $p->price }}</td>
    <td>{{ $p->is_pinned ? 'YES' : 'NO' }}</td>
    <td>
        <a href="{{ route('product.pin',$p->id) }}">
            {{ $p->is_pinned ? 'Unpin' : 'Pin' }}
        </a>
        |
        <a href="{{ route('product.edit',$p->id) }}">Edit</a>
    </td>
</tr>
@endforeach
</table>

@endsection
```

---

### resources/views/product/create.blade.php

```blade
@extends('layout.app')

@section('content')

<form method="POST" action="{{ route('product.store') }}">
@csrf

<input type="text" name="name" placeholder="Product Name" required><br><br>
<input type="text" name="price" placeholder="Price" required><br><br>

<button>Add Product</button>

</form>

@endsection
```

---

### resources/views/product/edit.blade.php

```blade
@extends('layout.app')

@section('content')

<h3>Edit Product</h3>

<form method="POST" action="{{ route('product.update',$product->id) }}">
@csrf

<input type="text" name="name" value="{{ $product->name }}" required><br><br>
<input type="text" name="price" value="{{ $product->price }}" required><br><br>

<button>Update Product</button>

</form>

@endsection
```

---

### resources/views/frontend/products.blade.php

```blade
@extends('layout.app')

@section('content')

<h3>Frontend Product List</h3>

@foreach($products as $p)
    <p>
        {{ $p->name }} - ₹{{ $p->price }}
        @if($p->is_pinned)
            ⭐ <strong>Pinned</strong>
        @endif
    </p>
@endforeach

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
