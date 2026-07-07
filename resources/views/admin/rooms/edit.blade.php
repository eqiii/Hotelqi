@extends('layouts.admin')

@section('pageTitle')
    Edit Room
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Edit Room</h2>

    <form method="POST" action="{{ route('admin.rooms.update', $room) }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div>
            <label>Room Number</label>
            <input name="room_number" value="{{ old('room_number', $room->room_number) }}" required />
        </div>
        <div>
            <label>Room Type</label>
            <select name="room_type_id">
                @foreach ($roomTypes as $t)
                    <option value="{{ $t->id }}" {{ $room->room_type_id == $t->id ? 'selected' : '' }}>
                        {{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Status</label>
            <select name="status">
                <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>available</option>
                <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>occupied</option>
                <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>maintenance</option>
            </select>
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png" />
            @if ($room->image)
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->room_number }}" class="h-32 rounded-lg object-cover" />
                </div>
            @endif
        </div>
        <button class="gold-btn mt-3">Save</button>
    </form>
@endsection
