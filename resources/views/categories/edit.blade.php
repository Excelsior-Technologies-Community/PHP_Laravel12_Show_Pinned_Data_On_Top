@extends('layout.app')

@section('content')

<h2>Edit Category</h2>

{{-- Category update form --}}
<form method="POST" action="{{ route('categories.update', $category->id) }}">
    @csrf

    {{-- Category name input --}}
    <div class="form-group">
        <label>Category Name</label>
        <input type="text" name="name" value="{{ $category->name }}">
    </div>

    {{-- Form action buttons --}}
    <div class="form-actions">
        <button class="btn btn-primary">Update Category</button>
        <a href="{{ route('categories.index') }}">
            <button type="button" class="btn btn-secondary">Back</button>
        </a>
    </div>
</form>

@endsection
