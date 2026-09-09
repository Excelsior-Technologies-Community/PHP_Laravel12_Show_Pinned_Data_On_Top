@extends('layout.app')

@section('content')

<div class="card-wrapper">


    {{-- Header --}}
    <div class="page-header">

        <div>

            <h2>
                Products
            </h2>

            <p class="page-subtitle">
                Manage products, pin priority and scheduled pinning.
            </p>

        </div>


        <div class="header-actions">

            <a href="{{ route('product.statistics') }}">

                <button class="btn btn-secondary">
                    📊 Pin Statistics
                </button>

            </a>


            <a href="{{ route('product.create') }}">

                <button class="btn btn-primary">
                    + Add Product
                </button>

            </a>

        </div>

    </div>


    {{-- Success message --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Product table --}}
    <table class="table clean-table">

        <thead>

            <tr>

                <th>#</th>

                <th>Pin Status</th>

                <th>Priority</th>

                <th>Name</th>

                <th>Details</th>

                <th>Category</th>

                <th>Price</th>

                <th>Image</th>

                <th>Schedule</th>

                <th>Action</th>

            </tr>

        </thead>


        <tbody>

            @forelse($products as $product)

                @php

                    $pinActive = $product->isPinCurrentlyActive();

                    $now = now();

                @endphp


                <tr>


                    {{-- Number --}}
                    <td>
                        {{ $loop->iteration }}
                    </td>


                    {{-- Pin Status --}}
                    <td>

                        @if($pinActive)

                            <span class="status-badge pinned">
                                📌 Pinned
                            </span>

                            <br>

                            <a
                                href="{{ route('product.pin', $product->id) }}"
                                class="small-link"
                            >
                                Unpin
                            </a>

                        @elseif($product->is_pinned && $product->pin_start_at && $product->pin_start_at->gt($now))

                            <span class="status-badge scheduled">
                                ⏰ Scheduled
                            </span>

                            <br>

                            <a
                                href="{{ route('product.pin', $product->id) }}"
                                class="small-link"
                            >
                                Unpin

                            </a>

                        @elseif($product->is_pinned && $product->pin_end_at && $product->pin_end_at->lt($now))

                            <span class="status-badge expired">
                                Expired
                            </span>

                            <br>

                            <a
                                href="{{ route('product.pin', $product->id) }}"
                                class="small-link"
                            >
                                Unpin
                            </a>

                        @else

                            <a
                                href="{{ route('product.pin', $product->id) }}"
                            >

                                <button class="btn btn-light btn-sm">
                                    📌 Pin
                                </button>

                            </a>

                        @endif

                    </td>


                    {{-- Priority --}}
                    <td>

                        @if($product->is_pinned)

                            <span class="priority-badge">
                                #{{ $product->pin_priority ?: 0 }}
                            </span>

                        @else

                            —

                        @endif

                    </td>


                    {{-- Name --}}
                    <td>

                        <strong>
                            {{ $product->name }}
                        </strong>

                    </td>


                    {{-- Details --}}
                    <td>

                        {{ Str::limit($product->details, 50) }}

                    </td>


                    {{-- Category --}}
                    <td>

                        {{ $product->category->name }}

                    </td>


                    {{-- Price --}}
                    <td>

                        ₹{{ number_format($product->price, 2) }}

                    </td>


                    {{-- Image --}}
                    <td>

                        @if($product->image)

                            <img
                                src="{{ asset('products/'.$product->image) }}"
                                class="table-img"
                            >

                        @else

                            —

                        @endif

                    </td>


                    {{-- Schedule --}}
                    <td>

                        @if($product->is_pinned)

                            @if($product->pin_start_at)

                                <div class="schedule-item">

                                    <strong>
                                        Start:
                                    </strong>

                                    {{ $product->pin_start_at->format('d M Y, h:i A') }}

                                </div>

                            @else

                                <div class="schedule-item">
                                    Start: Immediately
                                </div>

                            @endif


                            @if($product->pin_end_at)

                                <div class="schedule-item">

                                    <strong>
                                        End:
                                    </strong>

                                    {{ $product->pin_end_at->format('d M Y, h:i A') }}

                                </div>

                            @else

                                <div class="schedule-item">
                                    End: No expiry
                                </div>

                            @endif

                        @else

                            —

                        @endif

                    </td>


                    {{-- Actions --}}
                    <td>

                        <div class="action-buttons">

                            <a
                                href="{{ route('product.edit', $product->id) }}"
                            >

                                <button class="btn btn-light">
                                    Edit
                                </button>

                            </a>


                            <a
                                href="{{ route('product.delete', $product->id) }}"
                                onclick="return confirm('Delete this product?')"
                            >

                                <button class="btn btn-danger">
                                    Delete
                                </button>

                            </a>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="10"
                        class="empty-text"
                    >
                        No products found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection