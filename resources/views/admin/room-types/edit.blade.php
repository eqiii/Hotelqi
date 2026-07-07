@extends('layouts.admin')

@section('pageTitle')
    Edit Room Type
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Edit Room Type</h2>

    <form method="POST" action="{{ route('admin.room-types.update', $roomType) }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div>
            <label>Name</label>
            <input name="name" value="{{ old('name', $roomType->name) }}" required />
        </div>
        <div>
            <label>Price</label>
            <input name="base_price" value="{{ old('base_price', $roomType->base_price) }}" required type="number" step="0.01" />
        </div>
        <div>
            <label>Description</label>
            <textarea name="description">{{ old('description', $roomType->description) }}</textarea>
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" />
        </div>
        <button class="gold-btn mt-3">Save</button>
    </form>
@endsection
