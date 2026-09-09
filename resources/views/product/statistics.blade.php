@extends('layout.app')

@section('content')

<div class="card-wrapper">


    {{-- Header --}}
    <div class="page-header">

        <div>

            <h2>
                📊 Pinning Statistics
            </h2>

            <p class="page-subtitle">
                Overview of product pinning and scheduled promotions.
            </p>

        </div>


        <a href="{{ route('product.index') }}">

            <button class="btn btn-secondary">
                ← Back to Products
            </button>

        </a>

    </div>


    {{-- Statistics Cards --}}
    <div class="statistics-grid">


        {{-- Total --}}
        <div class="stat-card">

            <div class="stat-icon">
                📦
            </div>

            <div>

                <p>
                    Total Products
                </p>

                <h3>
                    {{ $totalProducts }}
                </h3>

            </div>

        </div>


        {{-- Pinned --}}
        <div class="stat-card pinned-stat">

            <div class="stat-icon">
                📌
            </div>

            <div>

                <p>
                    Total Pinned
                </p>

                <h3>
                    {{ $totalPinned }}
                </h3>

            </div>

        </div>


        {{-- Active --}}
        <div class="stat-card active-stat">

            <div class="stat-icon">
                ✅
            </div>

            <div>

                <p>
                    Currently Active
                </p>

                <h3>
                    {{ $activePinned }}
                </h3>

            </div>

        </div>


        {{-- Unpinned --}}
        <div class="stat-card">

            <div class="stat-icon">
                📋
            </div>

            <div>

                <p>
                    Unpinned
                </p>

                <h3>
                    {{ $totalUnpinned }}
                </h3>

            </div>

        </div>


        {{-- Scheduled --}}
        <div class="stat-card scheduled-stat">

            <div class="stat-icon">
                ⏰
            </div>

            <div>

                <p>
                    Scheduled
                </p>

                <h3>
                    {{ $scheduledPinned }}
                </h3>

            </div>

        </div>


        {{-- Expired --}}
        <div class="stat-card expired-stat">

            <div class="stat-icon">
                ⌛
            </div>

            <div>

                <p>
                    Expired
                </p>

                <h3>
                    {{ $expiredPinned }}
                </h3>

            </div>

        </div>

    </div>


    {{-- Pin Percentage --}}
    <div class="percentage-card">

        <div class="percentage-header">

            <h3>
                Pinning Coverage
            </h3>

            <strong>
                {{ $pinPercentage }}%
            </strong>

        </div>


        <div class="progress-bar">

            <div
                class="progress-fill"
                style="width: {{ min($pinPercentage, 100) }}%;"
            ></div>

        </div>


        <p>
            {{ $totalPinned }} of {{ $totalProducts }}
            products are configured as pinned.
        </p>

    </div>


    {{-- Top Priority Products --}}
    <div class="top-products-section">

        <h3>
            🏆 Top Priority Pinned Products
        </h3>


        @if($topPinnedProducts->count())

            <table class="table clean-table">

                <thead>

                    <tr>

                        <th>
                            Priority
                        </th>

                        <th>
                            Product
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Schedule
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($topPinnedProducts as $product)

                        <tr>

                            <td>

                                <span class="priority-badge">
                                    #{{ $product->pin_priority ?: 0 }}
                                </span>

                            </td>


                            <td>

                                <strong>
                                    {{ $product->name }}
                                </strong>

                            </td>


                            <td>
                                {{ $product->category->name }}
                            </td>


                            <td>

                                @if($product->isPinCurrentlyActive())

                                    <span class="status-badge pinned">
                                        Active
                                    </span>

                                @elseif(
                                    $product->pin_start_at &&
                                    $product->pin_start_at->gt(now())
                                )

                                    <span class="status-badge scheduled">
                                        Scheduled
                                    </span>

                                @else

                                    <span class="status-badge expired">
                                        Expired
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($product->pin_start_at)

                                    {{ $product->pin_start_at->format('d M Y, h:i A') }}

                                @else

                                    Immediately

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="empty-text">
                No pinned products available.
            </div>

        @endif

    </div>

</div>

@endsection