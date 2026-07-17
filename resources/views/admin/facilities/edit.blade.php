@extends('layouts.admin')

@section('pageTitle')
    Edit Facility
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Edit Facility</h2>

    <form method="POST" action="{{ route('admin.facilities.update', $facility) }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div>
            <label>Name</label>
            <input name="name" value="{{ old('name', $facility->name) }}" required />
        </div>
        <div>
            <label>Description</label>
            <textarea name="description">{{ old('description', $facility->description) }}</textarea>
        </div>
        <div>
            <label>Icon (CSS class / text, e.g. wifi)</label>
            <input name="icon" value="{{ old('icon', $facility->icon) }}" />
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png" />
            @if ($facility->image)
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}"
                        class="h-32 rounded-lg object-cover" />
                </div>
            @endif
        </div>
        <div>
            <label>Status</label>
            <select name="status">
                <option value="1" {{ old('status', $facility->status) == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('status', $facility->status) === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div>
            <label>Display Order</label>
            <input type="number" name="display_order" value="{{ old('display_order', $facility->display_order) }}" min="0" />
        </div>
        <button class="gold-btn mt-3">Save</button>
    </form>
@endsection
