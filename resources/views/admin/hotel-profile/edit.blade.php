<x-hotel-app-layout>
    <x-slot name="pageTitle">Hotel Profile</x-slot>

    <h2 class="font-playfair text-2xl font-bold">Hotel Profile</h2>

    <form method="POST" action="{{ route('admin.hotel-profile.update') }}" class="mt-4">@csrf @method('PATCH')
        <div>
            <label>Name</label>
            <input name="name" value="{{ old('name', $profile->name ?? '') }}" required />
        </div>
        <div>
            <label>Address</label>
            <textarea name="address">{{ old('address', $profile->address ?? '') }}</textarea>
        </div>
        <div>
            <label>Phone</label>
            <input name="phone" value="{{ old('phone', $profile->phone ?? '') }}" />
        </div>
        <div>
            <label>Email</label>
            <input name="email" value="{{ old('email', $profile->email ?? '') }}" />
        </div>
        <button class="gold-btn mt-3">Save</button>
    </form>
</x-hotel-app-layout>
