<div class="row mb-3">
    <div class="col-md-6">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $product->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label>Category</label>
        <select name="category_id" class="form-control" required>
            <option value="">Select</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label>Color</label>
        <select name="color_id" class="form-control" required>
            <option value="">Select</option>
            @foreach($colors as $color)
                <option value="{{ $color->id }}"
                    {{ old('color_id', $product->color_id ?? '') == $color->id ? 'selected' : '' }}>
                    {{ $color->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Size</label>
        <select name="size_id" class="form-control" required>
            <option value="">Select</option>
            @foreach($sizes as $size)
                <option value="{{ $size->id }}"
                    {{ old('size_id', $product->size_id ?? '') == $size->id ? 'selected' : '' }}>
                    {{ $size->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Qty</label>
        <input type="number" name="qty" class="form-control"
               value="{{ old('qty', $product->qty ?? '') }}" required>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label>Price</label>
        <input type="number" step="0.01" name="price" class="form-control"
               value="{{ old('price', $product->price ?? '') }}" required>
    </div>

    <div class="col-md-8">
        <label>Image (JPG / PNG)</label>
        <input type="file" name="image" class="form-control"
               {{ isset($product) ? '' : 'required' }}>
        @if(isset($product))
            <img src="{{ asset('storage/' . $product->image) }}" width="100" class="mt-2">
        @endif
    </div>
</div>
