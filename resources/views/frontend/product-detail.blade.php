@extends('layout.app')

@section('content')

<div class="product-detail-wrapper">

    {{-- Main product detail section --}}
    <div class="product-detail-grid">

        {{-- Product image --}}
        <div class="detail-image-box">
            @if($product->image)
                <img src="{{ asset('products/'.$product->image) }}">
            @else
                <div class="no-image">No Image</div>
            @endif
        </div>

        {{-- Product information --}}
        <div class="detail-content-box">
            <h2 class="detail-title">{{ $product->name }}</h2>

            <p class="detail-category">
                Category:
                <span>{{ $product->category->name }}</span>
            </p>

            {{-- Price label --}}
            <p class="price-label">Price</p>

            {{-- Product price --}}
            <p class="detail-price">
                ₹{{ number_format($product->selling_price,2) }}
                @if($product->discount_price)<del>₹{{ number_format($product->price,2) }}</del>@endif
            </p>

            @if($product->label)<p><strong>{{ $product->label }}</strong></p>@endif
            @if($product->brand)<p>Brand: {{ $product->brand }}</p>@endif
            <p>{{ $product->stock > 0 ? $product->stock.' available' : 'Currently unavailable' }}</p>

            @if($product->variants->count())
                <label>Options</label><select name="variant_id" form="cart-form">@foreach($product->variants as $variant)<option value="{{ $variant->id }}">{{ $variant->name }}: {{ $variant->value }}</option>@endforeach</select>
            @endif
            <form id="cart-form" method="POST" action="{{ route('cart.add', $product->id) }}">@csrf<input type="number" name="quantity" value="1" min="1" max="{{ max(1, $product->stock) }}" style="width:90px"><button class="btn btn-primary" {{ $product->stock === 0 ? 'disabled' : '' }}>Add to cart</button></form>
            <form method="POST" action="{{ route('wishlist.toggle', $product->id) }}" style="display:inline">@csrf<button class="btn btn-light">Wishlist</button></form>
            <form method="POST" action="{{ route('compare.toggle', $product->id) }}" style="display:inline">@csrf<button class="btn btn-light">Compare</button></form>
            <p><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener">Share product</a></p>

            {{-- Full description --}}
            <div class="detail-description">
                <h4>Product Details</h4>
                <p>{{ $product->details }}</p>
            </div>

            @if($product->specifications)<div><h4>Specifications</h4>@foreach($product->specifications as $key => $value)<p><strong>{{ $key }}:</strong> {{ $value }}</p>@endforeach</div>@endif
            @if($product->video_url)<p><a href="{{ $product->video_url }}" target="_blank" rel="noopener">Watch product video</a></p>@endif

            <h4>Reviews ({{ $product->reviews->count() }})</h4>
            @foreach($product->reviews as $review)<p><strong>{{ $review->name }}</strong> {{ str_repeat('★', $review->rating) }}<br>{{ $review->comment }}</p>@endforeach
            <form method="POST" action="{{ route('review.store', $product->id) }}">@csrf<input name="name" placeholder="Your name" required><select name="rating"><option value="5">5 stars</option><option value="4">4 stars</option><option value="3">3 stars</option><option value="2">2 stars</option><option value="1">1 star</option></select><textarea name="comment" placeholder="Your review"></textarea><button class="btn btn-secondary">Submit review</button></form>

            {{-- Back button --}}
            <div class="detail-actions">
                <a href="{{ route('frontend.products') }}">
                    <button class="btn btn-secondary">← Back</button>
                </a>
            </div>
        </div>

    </div>

    {{-- Related products section --}}
    @if($relatedProducts->count())
    <div class="related-products-section">
        <h3>More Related Products</h3>

        <div class="frontend-grid">
            @foreach($relatedProducts as $item)
                <div class="product-card modern">

                    {{-- Related product image --}}
                    <div class="image-wrap">
                        @if($item->image)
                            <img src="{{ asset('products/'.$item->image) }}">
                        @else
                            <div class="no-image">No Image</div>
                        @endif
                    </div>

                    {{-- Related product info --}}
                    <div class="card-body">
                        <h4 class="product-title">{{ $item->name }}</h4>

                        <p class="price">
                            ₹{{ number_format($item->price,2) }}
                        </p>

                        <a href="{{ route('frontend.product.detail', $item->id) }}">
                            <button class="btn btn-primary btn-sm">View Details</button>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(isset($recentProducts) && $recentProducts->count())<h3>Recently Viewed</h3><div class="frontend-grid">@foreach($recentProducts as $item)<a href="{{ route('frontend.product.detail', $item->id) }}">{{ $item->name }}</a>@endforeach</div>@endif

</div>

@endsection
