<x-hotel-app-layout>
    <x-slot name="pageTitle">Edit Guest</x-slot>

    <h2 class="font-playfair text-2xl font-bold">Edit Guest</h2>

    <form method="POST" action="{{ route('admin.guests.update', $guest) }}" class="mt-4">
        @csrf
        @method('PATCH')
        <div>
            <label>Phone</label>
            <input name="phone" value="{{ old('phone', $guest->phone) }}" />
        </div>
        <div>
            <label>Address</label>
            <textarea name="address">{{ old('address', $guest->address) }}</textarea>
        </div>
        <button class="gold-btn mt-3">Save</button>
    </form>
</x-hotel-app-layout>
