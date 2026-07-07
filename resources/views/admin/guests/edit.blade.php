@extends('layouts.admin')

@section('pageTitle')
    Edit Guest
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Edit Guest</h2>

    <form method="POST" action="{{ route('admin.guests.update', $guest) }}" class="mt-4 max-w-lg">
        @csrf
        @method('PATCH')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input name="name" value="{{ old('name', $guest->user->name) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input name="email" value="{{ old('email', $guest->user->email) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input name="phone" value="{{ old('phone', $guest->phone) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <textarea name="address" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">{{ old('address', $guest->address) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">KTP Number</label>
                <input name="ktp_number" value="{{ old('ktp_number', $guest->ktp_number) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
            </div>
            <button class="gold-btn px-6 py-2.5 rounded-lg font-semibold">Save</button>
        </div>
    </form>
@endsection
