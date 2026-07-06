<x-hotel-app-layout>
    <x-slot name="pageTitle">Room Types</x-slot>

    <div class="flex items-center justify-between mb-6">
        <h2 class="font-playfair text-2xl font-bold">Room Types</h2>
        <a href="{{ route('admin.room-types.create') }}" class="gold-btn">New</a>
    </div>

    <table class="w-full">
        <thead>
            <tr><th>Name</th><th>Price</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($types as $type)
                <tr>
                    <td>{{ $type->name }}</td>
                    <td>{{ format_rupiah($type->base_price ?? 0) }}</td>
                    <td>
                        <a href="{{ route('admin.room-types.edit', $type) }}">Edit</a>
                        <form action="{{ route('admin.room-types.destroy', $type) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button>Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $types->links() }}
</x-hotel-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tipe Kamar') }}</h2>
            <a href="{{ route('admin.room-types.create') }}"
                class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition">Tambah Tipe</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-lg border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                @if ($roomTypes->isEmpty())
                    <p class="text-center py-12 text-gray-500">Belum ada data tipe kamar.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Harga Dasar</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Kapasitas</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($roomTypes as $roomType)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $roomType->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ format_rupiah($roomType->base_price) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $roomType->max_guest }} orang</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                            <a href="{{ route('admin.room-types.edit', $roomType) }}"
                                                class="text-amber-600 hover:text-amber-700 font-semibold">Edit</a>
                                            <form action="{{ route('admin.room-types.destroy', $roomType) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-700 font-semibold"
                                                    onclick="return confirm('Hapus tipe kamar?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $roomTypes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
