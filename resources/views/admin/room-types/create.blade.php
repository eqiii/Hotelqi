@extends('layouts.admin')

@section('pageTitle')
    Create Room Type
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Create Room Type</h2>

    <form method="POST" action="{{ route('admin.room-types.store') }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Name</label>
            <input name="name" required />
        </div>
        <div>
            <label>Base Price</label>
            <input name="base_price" required type="number" step="0.01" />
        </div>
        <div>
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" />
        </div>
        <button class="gold-btn mt-3">Create</button>
    </form>
@endsection
