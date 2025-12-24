@extends('layout.app')

@section('content')

<h2>Edit Product</h2>

{{-- Product update form --}}
<form method="POST" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
    @csrf

    {{-- Product name --}}
    <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" value="{{ $product->name }}" required>
    </div>

    {{-- Product price --}}
    <div class="form-group">
        <label>Price</label>
        <input type="number" name="price" value="{{ $product->price }}" required>
    </div>

    {{-- Product details --}}
    <div class="form-group">
        <label>Details</label>
        <textarea name="details">{{ $product->details }}</textarea>
    </div>

    {{-- Category selection --}}
    <div class="form-group">
        <label>Category</label>
        <select name="category_id" required>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    @if($product->category_id == $cat->id) selected @endif>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Image preview (old + new) --}}
    <div class="form-group">
        <label>Product Image</label>

        <div class="image-preview-wrapper">

            {{-- Current image --}}
            <div>
                <small>Current Image</small>
                <div class="preview-box">
                    @if($product->image)
                        <img src="{{ asset('products/'.$product->image) }}">
                    @else
                        <span class="preview-text">No Image</span>
                    @endif
                </div>
            </div>

            {{-- New image preview --}}
            <div>
                <small>New Image</small>
                <div class="preview-box" id="newImagePreview">
                    <span class="preview-text">Select Image</span>
                </div>
            </div>
        </div>

        <br>

        <input type="file" name="image"
               onchange="previewImage(this, 'newImagePreview')">
    </div>

    {{-- Form actions --}}
    <div class="form-actions">
        <button class="btn btn-primary">Update Product</button>
        <a href="{{ route('product.index') }}">
            <button type="button" class="btn btn-secondary">Back</button>
        </a>
    </div>

</form>

@endsection
