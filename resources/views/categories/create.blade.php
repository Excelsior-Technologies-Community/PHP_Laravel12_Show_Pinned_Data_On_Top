@extends('layout.app')

@section('content')

<h2>Add Category</h2>

{{-- Category creation form --}}
<form method="POST" action="{{ route('categories.store') }}">
    @csrf

    {{-- Category name input --}}
    <div class="form-group">
        <label>Category Name</label>
        <input type="text" name="name" placeholder="Enter category name">
    </div>

    {{-- Form action buttons --}}
    <div class="form-actions">
        <button class="btn btn-primary">Save Category</button>
        <a href="{{ route('categories.index') }}">
            <button type="button" class="btn btn-secondary">Back</button>
        </a>
    </div>
</form>

@endsection
