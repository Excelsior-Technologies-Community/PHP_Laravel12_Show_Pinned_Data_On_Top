@extends('layout.app')

@section('content')

<div class="card-wrapper">

    {{-- Header --}}

    <div class="page-header">

        <div>

            <h2>
                Categories
            </h2>

            <p class="page-subtitle">
                Manage product categories.
            </p>

        </div>


        <a href="{{ route('categories.create') }}">

            <button class="btn btn-primary">
                + Add Category
            </button>

        </a>

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
    {{-- CATEGORY TABLE --}}
    {{-- ========================================================= --}}

    <div style="overflow-x:auto;">

        <table class="table clean-table">

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Category Name
                    </th>

                    <th>
                        Slug
                    </th>

                    <th>
                        Products
                    </th>

                    <th class="text-right">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($categories as $category)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>

                            <strong>
                                {{ $category->name }}
                            </strong>

                        </td>


                        <td>

                            <code>
                                {{ $category->slug }}
                            </code>

                        </td>


                        <td>

                            <span class="priority-badge">
                                {{ $category->products_count }}
                            </span>

                        </td>


                        <td class="text-right">

                            <div class="action-buttons">

                                <a
                                    href="{{ route('categories.edit', $category->id) }}"
                                >

                                    <button
                                        type="button"
                                        class="btn btn-light"
                                    >
                                        Edit
                                    </button>

                                </a>


                                @if($category->products_count == 0)

                                    <a
                                        href="{{ route('categories.delete', $category->id) }}"
                                        onclick="return confirm('Delete this category?')"
                                    >

                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                        >
                                            Delete
                                        </button>

                                    </a>

                                @else

                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        onclick="alert('This category has {{ $category->products_count }} product(s) and cannot be deleted.')"
                                    >
                                        Delete
                                    </button>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="empty-text"
                        >
                            No categories found.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection