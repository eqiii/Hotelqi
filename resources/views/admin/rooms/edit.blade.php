<x-hotel-app-layout>
    <x-slot name="pageTitle">Edit Room</x-slot>

    <h2 class="font-playfair text-2xl font-bold">Edit Room</h2>

    <form method="POST" action="{{ route('admin.rooms.update', $room) }}" class="mt-4">
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
        <button class="gold-btn mt-3">Save</button>
    </form>
</x-hotel-app-layout>
