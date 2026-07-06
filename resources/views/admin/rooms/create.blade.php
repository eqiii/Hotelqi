<x-hotel-app-layout>
    <x-slot name="pageTitle">Create Room</x-slot>

    <h2 class="font-playfair text-2xl font-bold">Create Room</h2>

    <form method="POST" action="{{ route('admin.rooms.store') }}" class="mt-4">
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
        <button class="gold-btn mt-3">Create</button>
    </form>
</x-hotel-app-layout>
