<x-hotel-app-layout>
    <x-slot name="pageTitle">Edit Room Type</x-slot>

    <h2 class="font-playfair text-2xl font-bold">Edit Room Type</h2>

    <form method="POST" action="{{ route('admin.room-types.update', $roomType) }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div>
            <label>Name</label>
            <input name="name" value="{{ old('name', $roomType->name) }}" required />
        </div>
        <div>
            <label>Price</label>
            <input name="base_price" value="{{ old('base_price', $roomType->base_price) }}" required type="number" step="0.01" />
        </div>
        <div>
            <label>Description</label>
            <textarea name="description">{{ old('description', $roomType->description) }}</textarea>
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" />
        </div>
        <button class="gold-btn mt-3">Save</button>
    </form>
</x-hotel-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Ubah Tipe Kamar') }}</h2>
            <a href="{{ route('admin.room-types.index') }}"
                class="text-amber-600 hover:text-amber-700 font-semibold">Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                <form action="{{ route('admin.room-types.update', $roomType) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tipe</label>
                        <input type="text" name="name" value="{{ old('name', $roomType->name) }}" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">{{ old('description', $roomType->description) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Dasar</label>
                            <input type="number" name="base_price"
                                value="{{ old('base_price', $roomType->base_price) }}" step="0.01" min="0"
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas</label>
                            <input type="number" name="max_guest" value="{{ old('max_guest', $roomType->max_guest) }}"
                                min="1" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kasur</label>
                            <input type="number" name="total_bed" value="{{ old('total_bed', $roomType->total_bed) }}"
                                min="1" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Tipe Kamar</label>
                        <input type="file" name="image" class="w-full text-gray-700" />
                        @if ($roomType->image)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $roomType->image) }}" alt="{{ $roomType->name }}"
                                    class="h-32 rounded-lg object-cover" />
                            </div>
                        @endif
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition font-semibold">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
