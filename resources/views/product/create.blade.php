@extends('layout.app')

@section('content')

<div class="card-wrapper">

    <div class="page-header">
        <h2>Add Product</h2>

        <a href="{{ route('product.index') }}">
            <button type="button" class="btn btn-secondary">
                Back
            </button>
        </a>
    </div>


    @if($errors->any())

        <div class="alert alert-error">

            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('product.store') }}"
        enctype="multipart/form-data"
    >

        @csrf


        {{-- Product Name --}}
        <div class="form-group">

            <label>Product Name</label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter product name"
                required
            >

        </div>


        {{-- Price --}}
        <div class="form-group">

            <label>Price</label>

            <input
                type="number"
                name="price"
                value="{{ old('price') }}"
                min="0"
                step="0.01"
                placeholder="Enter product price"
                required
            >

        </div>


        {{-- Details --}}
        <div class="form-group">

            <label>Details</label>

            <textarea
                name="details"
                placeholder="Enter product details"
            >{{ old('details') }}</textarea>

        </div>


        {{-- Category --}}
        <div class="form-group">

            <label>Category</label>

            <select name="category_id" required>

                <option value="">
                    Select Category
                </option>

                @foreach($categories as $cat)

                    <option
                        value="{{ $cat->id }}"
                        @selected(old('category_id') == $cat->id)
                    >
                        {{ $cat->name }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- Image --}}
        <div class="form-group">

            <label>Product Image</label>

            <div class="image-preview-wrapper">

                <input
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                    onchange="previewImage(this, 'createPreview')"
                >

                <div
                    class="preview-box"
                    id="createPreview"
                >
                    <span class="preview-text">
                        No Image
                    </span>
                </div>

            </div>

            <small>
                Maximum 2MB. JPG, JPEG, PNG or WEBP.
            </small>

        </div>


        {{-- Pin Product --}}
        <div class="pin-settings">

            <h3>📌 Pin Settings</h3>

            <label class="checkbox-label">

                <input
                    type="checkbox"
                    name="is_pinned"
                    id="is_pinned"
                    value="1"
                    onchange="togglePinSettings()"
                    {{ old('is_pinned') ? 'checked' : '' }}
                >

                Pin this product

            </label>


            <div
                id="pinSettings"
                class="pin-settings-fields"
                style="{{ old('is_pinned') ? '' : 'display:none;' }}"
            >

                {{-- Priority --}}
                <div class="form-group">

                    <label>
                        Pin Priority
                    </label>

                    <input
                        type="number"
                        name="pin_priority"
                        value="{{ old('pin_priority', 1) }}"
                        min="1"
                    >

                    <small>
                        Lower number = higher position.
                    </small>

                </div>


                {{-- Start --}}
                <div class="form-group">

                    <label>
                        Pin Start Date & Time
                    </label>

                    <input
                        type="datetime-local"
                        name="pin_start_at"
                        value="{{ old('pin_start_at') }}"
                    >

                    <small>
                        Leave empty to start immediately.
                    </small>

                </div>


                {{-- End --}}
                <div class="form-group">

                    <label>
                        Pin End Date & Time
                    </label>

                    <input
                        type="datetime-local"
                        name="pin_end_at"
                        value="{{ old('pin_end_at') }}"
                    >

                    <small>
                        Leave empty for no expiry.
                    </small>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="form-actions">

            <button class="btn btn-primary">
                Save Product
            </button>

            <a href="{{ route('product.index') }}">
                <button
                    type="button"
                    class="btn btn-secondary"
                >
                    Cancel
                </button>
            </a>

        </div>

    </form>

</div>


<script>

function togglePinSettings()
{
    const checkbox = document.getElementById('is_pinned');

    const settings = document.getElementById('pinSettings');

    if (checkbox.checked) {

        settings.style.display = 'block';

    } else {

        settings.style.display = 'none';

    }
}

</script>

@endsection