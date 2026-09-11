@extends('layout.app')
@section('content')
<h2>Shopping Cart</h2>
@forelse($products as $product)
<div style="display:flex;gap:20px;align-items:center;padding:15px 0;border-bottom:1px solid #ddd"><strong>{{ $product->name }}</strong><span>{{ $product->cart_quantity }} x ₹{{ number_format($product->selling_price, 2) }}</span><form method="POST" action="{{ route('cart.remove', $product->id) }}">@csrf @method('DELETE')<button class="btn btn-danger">Remove</button></form></div>
@empty<p>Your cart is empty.</p>@endforelse
@if($products->isNotEmpty())<h3>Total: ₹{{ number_format($subtotal, 2) }}</h3><a class="btn btn-primary" href="{{ route('checkout') }}">Checkout</a>@endif
@endsection
