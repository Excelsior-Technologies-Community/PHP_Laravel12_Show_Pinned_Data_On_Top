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

                    <span class="price">

                        ₹{{ number_format($product->price, 2) }}

                    </span>


                    <a
                        href="{{ route(
                            'frontend.product.detail',
                            $product->id
                        ) }}"
                    >

                        <button class="btn btn-primary btn-sm">

                            View Details

                        </button>

                    </a>

                </div>

            </div>

        </div>

    @empty

        <div class="empty-text">

            No products available.

        </div>

    @endforelse

</div>

@endsection