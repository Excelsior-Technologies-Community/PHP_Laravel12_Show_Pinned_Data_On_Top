@extends('layout.app')

@section('content')

<div class="card-wrapper">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERROR MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATISTICS --}}
    {{-- ========================================================= --}}

    <div class="statistics-grid">

        <div class="stat-card">

            <div class="stat-icon">
                📦
            </div>

            <div>
                <p>Total Products</p>

                <h3>
                    {{ $totalProducts }}
                </h3>
            </div>

        </div>


        <div class="stat-card pinned-stat">

            <div class="stat-icon">
                📌
            </div>

            <div>
                <p>Total Pinned</p>

                <h3>
                    {{ $totalPinned }}
                </h3>
            </div>

        </div>


        <div class="stat-card active-stat">

            <div class="stat-icon">
                ✅
            </div>

            <div>
                <p>Active Pinned</p>

                <h3>
                    {{ $activePinned }}
                </h3>
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                📋
            </div>

            <div>
                <p>Unpinned</p>

                <h3>
                    {{ $totalUnpinned }}
                </h3>
            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTERS --}}
    {{-- ========================================================= --}}

    <div
        style="
            margin: 25px 0;
            padding: 20px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        "
    >

        <h3 style="margin-bottom: 18px;">
            🔎 Product Filters
        </h3>


        <form
            method="GET"
            action="{{ route('product.index') }}"
        >

            <div
                style="
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                    gap: 15px;
                    align-items: end;
                "
            >

                {{-- ================================================= --}}
                {{-- SEARCH --}}
                {{-- ================================================= --}}

                <div>

                    <label>
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Product name/details"
                        class="form-control"
                    >

                </div>


                {{-- ================================================= --}}
                {{-- CATEGORY --}}
                {{-- ================================================= --}}

                <div>

                    <label>
                        Category
                    </label>

                    <select
                        name="category_id"
                        class="form-control"
                    >

                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                {{ $categoryId == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ================================================= --}}
                {{-- PIN STATUS --}}
                {{-- ================================================= --}}

                <div>

                    <label>
                        Pin Status
                    </label>

                    <select
                        name="pin_status"
                        class="form-control"
                    >

                        <option
                            value="all"
                            {{ $pinStatus == 'all' ? 'selected' : '' }}
                        >
                            All
                        </option>

                        <option
                            value="active"
                            {{ $pinStatus == 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="pinned"
                            {{ $pinStatus == 'pinned' ? 'selected' : '' }}
                        >
                            All Pinned
                        </option>

                        <option
                            value="scheduled"
                            {{ $pinStatus == 'scheduled' ? 'selected' : '' }}
                        >
                            Scheduled
                        </option>

                        <option
                            value="expired"
                            {{ $pinStatus == 'expired' ? 'selected' : '' }}
                        >
                            Expired
                        </option>

                        <option
                            value="unpinned"
                            {{ $pinStatus == 'unpinned' ? 'selected' : '' }}
                        >
                            Unpinned
                        </option>

                    </select>

                </div>


                {{-- ================================================= --}}
                {{-- MIN PRICE --}}
                {{-- ================================================= --}}

                <div>

                    <label>
                        Min Price
                    </label>

                    <input
                        type="number"
                        name="min_price"
                        value="{{ $minPrice }}"
                        min="0"
                        step="0.01"
                        placeholder="₹ Minimum"
                        class="form-control"
                    >

                </div>


                {{-- ================================================= --}}
                {{-- MAX PRICE --}}
                {{-- ================================================= --}}

                <div>

                    <label>
                        Max Price
                    </label>

                    <input
                        type="number"
                        name="max_price"
                        value="{{ $maxPrice }}"
                        min="0"
                        step="0.01"
                        placeholder="₹ Maximum"
                        class="form-control"
                    >

                </div>


                {{-- ================================================= --}}
                {{-- SORT --}}
                {{-- ================================================= --}}

                <div>

                    <label>
                        Sort By
                    </label>

                    <select
                        name="sort"
                        class="form-control"
                    >

                        <option
                            value="id"
                            {{ $sort == 'id' ? 'selected' : '' }}
                        >
                            ID
                        </option>

                        <option
                            value="name"
                            {{ $sort == 'name' ? 'selected' : '' }}
                        >
                            Name
                        </option>

                        <option
                            value="price"
                            {{ $sort == 'price' ? 'selected' : '' }}
                        >
                            Price
                        </option>

                        <option
                            value="pin_priority"
                            {{ $sort == 'pin_priority' ? 'selected' : '' }}
                        >
                            Pin Priority
                        </option>

                        <option
                            value="created_at"
                            {{ $sort == 'created_at' ? 'selected' : '' }}
                        >
                            Created Date
                        </option>

                    </select>

                </div>


                {{-- ================================================= --}}
                {{-- DIRECTION --}}
                {{-- ================================================= --}}

                <div>

                    <label>
                        Direction
                    </label>

                    <select
                        name="direction"
                        class="form-control"
                    >

                        <option
                            value="asc"
                            {{ $direction == 'asc' ? 'selected' : '' }}
                        >
                            Ascending
                        </option>

                        <option
                            value="desc"
                            {{ $direction == 'desc' ? 'selected' : '' }}
                        >
                            Descending
                        </option>

                    </select>

                </div>


                {{-- ================================================= --}}
                {{-- FILTER BUTTONS --}}
                {{-- ================================================= --}}

                <div>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        🔎 Apply Filters
                    </button>

                    <a
                        href="{{ route('product.index') }}"
                        class="btn btn-secondary"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- BULK ACTIONS --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route('product.bulk-action') }}"
        id="bulkForm"
    >

        @csrf


        <div
            style="
                display: flex;
                gap: 10px;
                align-items: center;
                flex-wrap: wrap;
                margin-bottom: 18px;
            "
        >

            <strong>
                Bulk Actions:
            </strong>


            {{-- PIN --}}

            <button
                type="submit"
                name="action"
                value="pin"
                class="btn btn-secondary"
                onclick="return validateBulkAction('pin')"
            >
                📌 Pin Selected
            </button>


            {{-- UNPIN --}}

            <button
                type="submit"
                name="action"
                value="unpin"
                class="btn btn-secondary"
                onclick="return validateBulkAction('unpin')"
            >
                📍 Unpin Selected
            </button>


            {{-- DELETE --}}

            <button
                type="submit"
                name="action"
                value="delete"
                class="btn btn-danger"
                onclick="return validateBulkAction('delete')"
            >
                🗑️ Delete Selected
            </button>


            {{-- CSV EXPORT --}}

            <a
                href="{{ route('product.export-csv', request()->query()) }}"
                class="btn btn-primary"
            >
                📥 Export CSV
            </a>

        </div>


        {{-- ===================================================== --}}
        {{-- PRODUCT TABLE --}}
        {{-- ===================================================== --}}

        <div style="overflow-x: auto;">

            <table class="table clean-table">

                <thead>

                    <tr>

                        <th>
                            <input
                                type="checkbox"
                                id="selectAll"
                            >
                        </th>

                        <th>
                            #
                        </th>

                        <th>
                            Pin Status
                        </th>

                        <th>
                            Priority
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Details
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Image
                        </th>

                        <th>
                            Schedule
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($products as $product)

                        @php

                            $pinActive =
                                $product->isPinCurrentlyActive();

                            $now = now();

                        @endphp


                        <tr>

                            {{-- ================================================= --}}
                            {{-- CHECKBOX --}}
                            {{-- ================================================= --}}

                            <td>

                                <input
                                    type="checkbox"
                                    name="ids[]"
                                    value="{{ $product->id }}"
                                    class="product-checkbox"
                                >

                            </td>


                            {{-- ================================================= --}}
                            {{-- NUMBER --}}
                            {{-- ================================================= --}}

                            <td>

                                {{ $products->firstItem() + $loop->index }}

                            </td>


                            {{-- ================================================= --}}
                            {{-- PIN STATUS --}}
                            {{-- ================================================= --}}

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


                                @elseif(
                                    $product->is_pinned &&
                                    $product->pin_start_at &&
                                    $product->pin_start_at->gt($now)
                                )

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


                                @elseif(
                                    $product->is_pinned &&
                                    $product->pin_end_at &&
                                    $product->pin_end_at->lt($now)
                                )

                                    <span class="status-badge expired">
                                        ⌛ Expired
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

                                        <button
                                            type="button"
                                            class="btn btn-light btn-sm"
                                        >
                                            📌 Pin
                                        </button>

                                    </a>

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- PRIORITY --}}
                            {{-- ================================================= --}}

                            <td>

                                @if($product->is_pinned)

                                    <span class="priority-badge">
                                        #{{ $product->pin_priority ?: 0 }}
                                    </span>

                                @else

                                    —

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- NAME --}}
                            {{-- ================================================= --}}

                            <td>

                                <strong>
                                    {{ $product->name }}
                                </strong>

                            </td>


                            {{-- ================================================= --}}
                            {{-- DETAILS --}}
                            {{-- ================================================= --}}

                            <td>

                                {{ Str::limit($product->details, 50) }}

                            </td>


                            {{-- ================================================= --}}
                            {{-- CATEGORY --}}
                            {{-- ================================================= --}}

                            <td>

                                {{ $product->category
                                    ? $product->category->name
                                    : '—'
                                }}

                            </td>


                            {{-- ================================================= --}}
                            {{-- PRICE --}}
                            {{-- ================================================= --}}

                            <td>

                                ₹{{ number_format($product->price, 2) }}

                            </td>


                            {{-- ================================================= --}}
                            {{-- IMAGE --}}
                            {{-- ================================================= --}}

                            <td>

                                @if($product->image)

                                    <img
                                        src="{{ asset('products/'.$product->image) }}"
                                        class="table-img"
                                        alt="{{ $product->name }}"
                                    >

                                @else

                                    —

                                @endif

                            </td>


                            {{-- ================================================= --}}
                            {{-- SCHEDULE --}}
                            {{-- ================================================= --}}

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


                            {{-- ================================================= --}}
                            {{-- ACTIONS --}}
                            {{-- ================================================= --}}

                            <td>

                                <div class="action-buttons">

                                    <a
                                        href="{{ route('product.edit', $product->id) }}"
                                    >

                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            Edit
                                        </button>

                                    </a>


                                    <a
                                        href="{{ route('product.delete', $product->id) }}"
                                        onclick="return confirm('Delete this product?')"
                                    >

                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                        >
                                            Delete
                                        </button>

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="11"
                                class="empty-text"
                            >
                                No products found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </form>


    {{-- ========================================================= --}}
    {{-- PAGINATION - NUMBER ONLY --}}
    {{-- ========================================================= --}}

    @if($products->hasPages())

        <div
            style="
                margin-top: 25px;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 8px;
                flex-wrap: wrap;
            "
        >

            {{-- ONLY NUMBERS --}}
            @foreach(
                $products->getUrlRange(
                    1,
                    $products->lastPage()
                )
                as $page => $url
            )

                @if($page == $products->currentPage())

                    <span
                        style="
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            min-width: 40px;
                            height: 40px;
                            padding: 0 12px;
                            border-radius: 8px;
                            background: #2563eb;
                            color: white;
                            font-weight: 600;
                            border: 1px solid #2563eb;
                        "
                    >
                        {{ $page }}
                    </span>

                @else

                    <a
                        href="{{ $url }}"
                        style="
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            min-width: 40px;
                            height: 40px;
                            padding: 0 12px;
                            border-radius: 8px;
                            background: white;
                            color: #374151;
                            font-weight: 600;
                            text-decoration: none;
                            border: 1px solid #d1d5db;
                        "
                    >
                        {{ $page }}
                    </a>

                @endif

            @endforeach

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- PAGINATION INFORMATION --}}
    {{-- ========================================================= --}}

    @if($products->total() > 0)

        <div
            style="
                margin-top: 15px;
                text-align: center;
                color: #6b7280;
                font-size: 14px;
            "
        >

            Showing
            <strong>
                {{ $products->firstItem() }}
            </strong>

            to

            <strong>
                {{ $products->lastItem() }}
            </strong>

            of

            <strong>
                {{ $products->total() }}
            </strong>

            products

        </div>

    @endif

</div>


{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('selectAll')
        ?.addEventListener('change', function () {

            document
                .querySelectorAll('.product-checkbox')
                .forEach(function (checkbox) {

                    checkbox.checked =
                        document.getElementById('selectAll').checked;

                });

        });


    /*
    |--------------------------------------------------------------------------
    | Bulk Action Validation
    |--------------------------------------------------------------------------
    */

    function validateBulkAction(action)
    {

        const selected =
            document.querySelectorAll(
                '.product-checkbox:checked'
            );


        if (selected.length === 0) {

            alert(
                'Please select at least one product.'
            );

            return false;

        }


        if (action === 'delete') {

            return confirm(
                'Are you sure you want to delete the selected products?'
            );

        }


        if (action === 'pin') {

            return confirm(
                'Pin all selected products?'
            );

        }


        if (action === 'unpin') {

            return confirm(
                'Unpin all selected products?'
            );

        }


        return true;

    }

</script>

@endsection