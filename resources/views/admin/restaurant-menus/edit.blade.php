@extends('layouts.admin')

@section('pageTitle')
    Edit Menu
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Edit Menu Item</h2>

    <form method="POST" action="{{ route('admin.restaurant-menus.update', $restaurantMenu) }}" class="mt-4" enctype="multipart/form-data">@csrf
        @method('PATCH')
        <div>
            <label>Name</label>
            <input name="name" value="{{ old('name', $restaurantMenu->name) }}" required />
        </div>
        <div>
            <label>Category</label>
            <select name="category" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" {{ old('category', $restaurantMenu->category) == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Price</label>
            <input name="price" value="{{ old('price', $restaurantMenu->price) }}" required type="number"
                step="0.01" />
        </div>
        <div>
            <label>Description</label>
            <textarea name="description">{{ old('description', $restaurantMenu->description) }}</textarea>
        </div>
        <div>
            <label>Stock Quantity</label>
            <input name="stock_quantity" value="{{ old('stock_quantity', $restaurantMenu->stock_quantity ?? 0) }}" type="number" min="0" />
        </div>
        <div>
            <label>Available</label>
            <input type="checkbox" name="is_available" value="1" {{ old('is_available', $restaurantMenu->is_available) ? 'checked' : '' }} />
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png" />
            @if ($restaurantMenu->image)
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $restaurantMenu->image) }}" alt="{{ $restaurantMenu->name }}" class="h-32 rounded-lg object-cover" />
                </div>
            @endif
        </div>
        <button class="gold-btn mt-3">Save</button>
    </form>
@endsection
