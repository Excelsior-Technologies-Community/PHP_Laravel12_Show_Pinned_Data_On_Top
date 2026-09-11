@extends('layout.app')
@section('content')
<h2>Compare Products</h2>
@if($products->isEmpty())<p>No products selected for comparison.</p>@else
<table><thead><tr><th>Product</th>@foreach($products as $product)<th>{{ $product->name }}</th>@endforeach</tr></thead><tbody>
<tr><th>Price</th>@foreach($products as $product)<td>₹{{ number_format($product->selling_price, 2) }}</td>@endforeach</tr>
<tr><th>Brand</th>@foreach($products as $product)<td>{{ $product->brand ?: '-' }}</td>@endforeach</tr>
<tr><th>Label</th>@foreach($products as $product)<td>{{ $product->label ?: '-' }}</td>@endforeach</tr>
<tr><th>Stock</th>@foreach($products as $product)<td>{{ $product->stock }}</td>@endforeach</tr>
<tr><th>Specifications</th>@foreach($products as $product)<td>@foreach(($product->specifications ?: []) as $key => $value){{ $key }}: {{ $value }}<br>@endforeach</td>@endforeach</tr>
</tbody></table>@endif
@endsection
