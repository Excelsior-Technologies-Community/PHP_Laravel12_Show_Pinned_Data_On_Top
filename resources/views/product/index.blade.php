@extends('layout.app')

@section('content')

<div class="card-wrapper">

    {{-- Page heading and add product button --}}
    <div class="page-header">
        <h2>Products</h2>

        <a href="{{ route('product.create') }}">
            <button class="btn btn-primary">+ Add Product</button>
        </a>
    </div>

    {{-- Product listing table --}}
    <table class="table clean-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Pin</th>
                <th>Name</th>
                <th>Details</th>
                <th>Category</th>
                <th>Price</th>
                <th>Image</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>

        <tbody>
            {{-- Loop through products --}}
            @forelse($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>

                {{-- Pin / Unpin button --}}
                <td>
                    @if($product->is_pinned)
                        <span style="color:green;font-weight:bold;">📌 Pinned</span>
                        <br>
                        <a href="{{ route('product.pin', $product->id) }}">
                            <small>Unpin</small>
                        </a>
                    @else
                        <a href="{{ route('product.pin', $product->id) }}">
                            <button class="btn btn-light btn-sm">📌 Pin</button>
                        </a>
                    @endif
                </td>

                <td><strong>{{ $product->name }}</strong></td>

                {{-- Short product description --}}
                <td>{{ Str::limit($product->details, 50) }}</td>

                {{-- Product category --}}
                <td>{{ $product->category->name }}</td>

                {{-- Product price --}}
                <td>₹{{ number_format($product->price, 2) }}</td>

                {{-- Product image --}}
                <td>
                    @if($product->image)
                        <img src="{{ asset('products/'.$product->image) }}" class="table-img">
                    @else
                        —
                    @endif
                </td>

                {{-- Edit and delete actions --}}
                <td class="text-right">
                    <a href="{{ route('product.edit', $product->id) }}">
                        <button class="btn btn-light">Edit</button>
                    </a>

                    <a href="{{ route('product.delete', $product->id) }}"
                       onclick="return confirm('Delete this product?')">
                        <button class="btn btn-danger">Delete</button>
                    </a>
                </td>
            </tr>

            {{-- Empty state --}}
            @empty
            <tr>
                <td colspan="8" class="empty-text">No products found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection
