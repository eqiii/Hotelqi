@extends('layouts.admin')

@section('pageTitle')
    Create Room
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Create Room</h2>

    <form method="POST" action="{{ route('admin.rooms.store') }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Room Number</label>
            <input name="room_number" required />
        </div>
        <div>
            <label>Room Type</label>
            <select name="room_type_id">
                @foreach ($roomTypes as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Status</label>
            <select name="status">
                <option value="available">available</option>
                <option value="occupied">occupied</option>
                <option value="maintenance">maintenance</option>
            </select>
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png" />
        </div>
        <button class="gold-btn mt-3">Create</button>
    </form>
@endsection
