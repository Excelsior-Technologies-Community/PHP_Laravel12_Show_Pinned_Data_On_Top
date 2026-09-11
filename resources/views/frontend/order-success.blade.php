@extends('layout.app')
@section('content')
<h2>Order Placed</h2><p>Your order <strong>{{ $order->order_number }}</strong> was placed successfully.</p><p>Total: ₹{{ number_format($order->total, 2) }}</p><a class="btn btn-primary" href="{{ route('shop.products') }}">Continue Shopping</a>
@endsection
