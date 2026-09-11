@extends('layout.app')

@section('content')

<div class="frontend-header">

    <div>

        <h2>
            Our Products
        </h2>

        <p class="page-subtitle">
            Discover our featured and regular products.
        </p>

    </div>

</div>

<form method="GET" action="{{ route('shop.products') }}" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px;margin:20px 0">
    <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search products, brands">
    <select name="category_id"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select>
    <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min price">
    <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max price">
    <select name="sort"><option value="featured">Featured</option><option value="newest">Newest</option><option value="price_asc">Price: Low to high</option><option value="price_desc">Price: High to low</option></select>
    <button class="btn btn-primary" type="submit">Filter</button>
</form>
<p><a href="{{ route('wishlist.index') }}">Wishlist ({{ count($wishlistIds ?? []) }})</a> | <a href="{{ route('compare.index') }}">Compare</a> | <a href="{{ route('cart.index') }}">Cart</a></p>

<div class="frontend-grid">

    @forelse($products as $product)

        @php
            $pinActive = $product->isPinCurrentlyActive();
        @endphp


        <div
            class="product-card modern
            {{ $pinActive ? 'featured-product' : '' }}"
        >


            {{-- Pinned badge --}}
            @if($pinActive)

                <span class="pinned-badge">
                    📌 PINNED
                </span>

            @endif

            @if($product->label)<span class="priority-card-badge">{{ $product->label }}</span>@endif


            {{-- Priority badge --}}
            @if($pinActive)

                <span class="priority-card-badge">
                    Priority #{{ $product->pin_priority }}
                </span>

            @endif


            {{-- Product image --}}
            <div class="image-wrap">

                @if($product->image)

                    <img
                        src="{{ asset('products/'.$product->image) }}"
                        alt="{{ $product->name }}"
                    >

                @else

                    <div class="no-image">
                        No Image
                    </div>

                @endif

            </div>


            {{-- Product body --}}
            <div class="card-body">


                <h4 class="product-title">
                    {{ $product->name }}
                </h4>


                <p class="category">
                    {{ $product->category->name }}
                </p>


                <p class="details">

                    {{ Str::limit($product->details, 70) }}

                </p>


                <div class="card-footer">

                    <span class="price">₹{{ number_format($product->selling_price, 2) }} @if($product->discount_price)<del>₹{{ number_format($product->price, 2) }}</del>@endif</span>


                    <a
                        href="{{ route(
                            'frontend.product.detail',
                            $product->id
                        ) }}"
                    >

                        <button class="btn btn-primary btn-sm">View Details</button>

                    </a>
                    <form method="POST" action="{{ route('wishlist.toggle', $product->id) }}" style="display:inline">@csrf<button class="btn btn-light btn-sm">♡</button></form>
                    <form method="POST" action="{{ route('compare.toggle', $product->id) }}" style="display:inline">@csrf<button class="btn btn-light btn-sm">Compare</button></form>
                    <form method="POST" action="{{ route('cart.add', $product->id) }}" style="display:inline">@csrf<button class="btn btn-secondary btn-sm" {{ $product->stock === 0 ? 'disabled' : '' }}>Add to cart</button></form>

                </div>

            </div>

        </div>

    @empty

        <div class="empty-text">

            No products available.

        </div>

    @endforelse

</div>

{{ $products->links() }}

@endsection