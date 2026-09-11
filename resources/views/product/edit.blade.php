@extends('layout.app')

@section('content')

<div class="card-wrapper">

    <div class="page-header">

        <h2>Edit Product</h2>

        <a href="{{ route('product.index') }}">
            <button
                type="button"
                class="btn btn-secondary"
            >
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
        action="{{ route('product.update', $product->id) }}"
        enctype="multipart/form-data"
    >

        @csrf


        {{-- Product Name --}}
        <div class="form-group">

            <label>Product Name</label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $product->name) }}"
                required
            >

        </div>

        <div class="form-group"><label>Original Price</label><input type="number" name="original_price" value="{{ old('original_price', $product->original_price) }}" min="0" step="0.01"></div>
        <div class="form-group"><label>Discount Price</label><input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" min="0" step="0.01"></div>
        <div class="form-group"><label>Label</label><select name="label"><option value="">None</option>@foreach(['New','Sale','Popular'] as $label)<option value="{{ $label }}" @selected(old('label', $product->label) === $label)>{{ $label }}</option>@endforeach</select></div>
        <div class="form-group"><label>Brand</label><input name="brand" value="{{ old('brand', $product->brand) }}"></div>
        <div class="form-group"><label>Tags (comma separated)</label><input name="tags" value="{{ old('tags', is_array($product->tags) ? implode(', ', $product->tags) : '') }}"></div>
        <div class="form-group"><label>Specifications (JSON)</label><textarea name="specifications">{{ old('specifications', $product->specifications ? json_encode($product->specifications) : '') }}</textarea></div>
        <div class="form-group"><label>Product Video URL</label><input type="url" name="video_url" value="{{ old('video_url', $product->video_url) }}"></div>
        <div class="form-group"><label>Stock</label><input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"></div>
        <div class="form-group"><label>Low Stock Warning At</label><input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0"></div>


        {{-- Price --}}
        <div class="form-group">

            <label>Price</label>

            <input
                type="number"
                name="price"
                value="{{ old('price', $product->price) }}"
                min="0"
                step="0.01"
                required
            >

        </div>


        {{-- Details --}}
        <div class="form-group">

            <label>Details</label>

            <textarea name="details">{{ old('details', $product->details) }}</textarea>

        </div>


        {{-- Category --}}
        <div class="form-group">

            <label>Category</label>

            <select name="category_id" required>

                @foreach($categories as $cat)

                    <option
                        value="{{ $cat->id }}"
                        @selected(
                            old(
                                'category_id',
                                $product->category_id
                            ) == $cat->id
                        )
                    >
                        {{ $cat->name }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- Images --}}
        <div class="form-group">

            <label>Product Image</label>

            <div class="image-preview-wrapper">


                {{-- Current Image --}}
                <div>

                    <small>
                        Current Image
                    </small>

                    <div class="preview-box">

                        @if($product->image)

                            <img
                                src="{{ asset('products/'.$product->image) }}"
                            >

                        @else

                            <span class="preview-text">
                                No Image
                            </span>

                        @endif

                    </div>

                </div>


                {{-- New Image --}}
                <div>

                    <small>
                        New Image
                    </small>

                    <div
                        class="preview-box"
                        id="newImagePreview"
                    >
                        <span class="preview-text">
                            Select Image
                        </span>
                    </div>

                </div>

            </div>


            <br>


            <input
                type="file"
                name="image"
                accept=".jpg,.jpeg,.png,.webp"
                onchange="previewImage(this, 'newImagePreview')"
            >

        </div>


        {{-- Pin Settings --}}
        <div class="pin-settings">

            <h3>📌 Pin Settings</h3>


            <label class="checkbox-label">

                <input
                    type="checkbox"
                    name="is_pinned"
                    id="is_pinned"
                    value="1"
                    onchange="togglePinSettings()"
                    {{ old('is_pinned', $product->is_pinned) ? 'checked' : '' }}
                >

                Pin this product

            </label>


            <div
                id="pinSettings"
                class="pin-settings-fields"
                style="{{ old('is_pinned', $product->is_pinned) ? '' : 'display:none;' }}"
            >


                {{-- Priority --}}
                <div class="form-group">

                    <label>
                        Pin Priority
                    </label>

                    <input
                        type="number"
                        name="pin_priority"
                        value="{{ old('pin_priority', $product->pin_priority ?: 1) }}"
                        min="1"
                    >

                    <small>
                        Lower number = higher position.
                    </small>

                </div>


                {{-- Start Date --}}
                <div class="form-group">

                    <label>
                        Pin Start Date & Time
                    </label>

                    <input
                        type="datetime-local"
                        name="pin_start_at"
                        value="{{
                            old(
                                'pin_start_at',
                                $product->pin_start_at
                                    ? $product->pin_start_at->format('Y-m-d\TH:i')
                                    : ''
                            )
                        }}"
                    >

                    <small>
                        Leave empty to start immediately.
                    </small>

                </div>


                {{-- End Date --}}
                <div class="form-group">

                    <label>
                        Pin End Date & Time
                    </label>

                    <input
                        type="datetime-local"
                        name="pin_end_at"
                        value="{{
                            old(
                                'pin_end_at',
                                $product->pin_end_at
                                    ? $product->pin_end_at->format('Y-m-d\TH:i')
                                    : ''
                            )
                        }}"
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
                Update Product
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