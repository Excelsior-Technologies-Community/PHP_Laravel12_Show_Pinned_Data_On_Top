@extends('layout.app')

@section('content')

<div class="card-wrapper">

    {{-- Page heading and add category button --}}
    <div class="page-header">
        <h2>Categories</h2>

        <a href="{{ route('categories.create') }}">
            <button class="btn btn-primary">Add Category</button>
        </a>
    </div>

    {{-- Category listing table --}}
    <table class="table clean-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>

        <tbody>
            {{-- Loop through categories --}}
            @forelse($categories as $category)
            <tr>
                <td>{{ $loop->iteration }}</td>

                {{-- Category name --}}
                <td>{{ $category->name }}</td>

                {{-- Edit and delete actions --}}
                <td class="text-right">
                    <a href="{{ route('categories.edit', $category->id) }}">
                        <button class="btn btn-light">Edit</button>
                    </a>

                    <a href="{{ route('categories.delete', $category->id) }}"
                       onclick="return confirm('Delete this category?')">
                        <button class="btn btn-danger">Delete</button>
                    </a>
                </td>
            </tr>

            {{-- Empty state --}}
            @empty
            <tr>
                <td colspan="3" class="empty-text">No categories found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection
