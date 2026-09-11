@extends('layout.app')
@section('content')
<h2>Order History</h2>
@forelse($orders as $order)
<div class="card-wrapper"><h3>{{ $order->order_number }}</h3><p>Status: {{ ucfirst($order->status) }} | Total: ₹{{ number_format($order->total, 2) }}</p>@foreach($order->items as $item)<p>{{ $item->product_name }} x {{ $item->quantity }}</p>@endforeach</div>
@empty<p>No orders found.</p>@endforelse
@endsection