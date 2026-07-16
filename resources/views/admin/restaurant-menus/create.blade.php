@extends('layouts.admin')

@section('pageTitle')
    Create Menu
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Create Menu Item</h2>

    <form method="POST" action="{{ route('admin.restaurant-menus.store') }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Name</label>
            <input name="name" required />
        </div>
        <div>
            <label>Category</label>
            <select name="category" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Price</label>
            <input name="price" required type="number" step="0.01" />
        </div>
        <div>
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>
        <div>
            <label>Stock Quantity</label>
            <input name="stock_quantity" type="number" min="0" value="0" />
        </div>
        <div>
            <label>Available</label>
            <input type="checkbox" name="is_available" value="1" checked />
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png" />
        </div>
        <button class="gold-btn mt-3">Create</button>
    </form>
@endsection
