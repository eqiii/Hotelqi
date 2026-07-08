@extends('layouts.admin')

@section('pageTitle')
    Create Facility
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Create Facility</h2>

    <form method="POST" action="{{ route('admin.facilities.store') }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Name</label>
            <input name="name" value="{{ old('name') }}" required />
        </div>
        <div>
            <label>Description</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>
        <div>
            <label>Icon (CSS class / text, e.g. wifi)</label>
            <input name="icon" value="{{ old('icon') }}" />
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png" />
        </div>
        <button class="gold-btn mt-3">Create</button>
    </form>
@endsection
