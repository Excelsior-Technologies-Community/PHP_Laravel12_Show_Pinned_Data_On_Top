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
                ₹{{ number_format($product->price,2) }}
            </p>

            {{-- Full description --}}
            <div class="detail-description">
                <h4>Product Details</h4>
                <p>{{ $product->details }}</p>
            </div>

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

</div>

@endsection
