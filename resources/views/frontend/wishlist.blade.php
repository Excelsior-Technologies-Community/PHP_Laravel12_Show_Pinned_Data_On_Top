@extends('layout.app')
@section('content')
<h2>Wishlist</h2>
<div class="frontend-grid">
@forelse($products as $product)
<div class="product-card modern"><div class="image-wrap">@if($product->image)<img src="{{ asset('products/'.$product->image) }}" alt="{{ $product->name }}">@else<div class="no-image">No Image</div>@endif</div><div class="card-body"><h4>{{ $product->name }}</h4><p>₹{{ number_format($product->selling_price, 2) }}</p><a class="btn btn-primary" href="{{ route('frontend.product.detail', $product->id) }}">View Details</a><form method="POST" action="{{ route('cart.add', $product->id) }}" style="display:inline">@csrf<button class="btn btn-secondary">Add to cart</button></form></div></div>
@empty<p>Your wishlist is empty.</p>@endforelse
</div>
@endsection
