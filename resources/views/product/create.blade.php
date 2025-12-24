@extends('layout.app')

@section('content')

<h2>Add Product</h2>

{{-- Product creation form --}}
<form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
    @csrf

    {{-- Product name --}}
    <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" required>
    </div>

    {{-- Product price --}}
    <div class="form-group">
        <label>Price</label>
        <input type="number" name="price" required>
    </div>

    {{-- Product details --}}
    <div class="form-group">
        <label>Details</label>
        <textarea name="details"></textarea>
    </div>

    {{-- Category selection --}}
    <div class="form-group">
        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Image upload with preview --}}
    <div class="form-group">
        <label>Product Image</label>

        <div class="image-preview-wrapper">
            <input type="file" name="image"
                   onchange="previewImage(this, 'createPreview')">

            <div class="preview-box" id="createPreview">
                <span class="preview-text">No Image</span>
            </div>
        </div>
    </div>

    {{-- Form actions --}}
    <div class="form-actions">
        <button class="btn btn-primary">Save Product</button>
        <a href="{{ route('product.index') }}">
            <button type="button" class="btn btn-secondary">Back</button>
        </a>
    </div>

</form>

@endsection
