<x-hotel-app-layout>
    <x-slot name="pageTitle">Rooms</x-slot>

    <div class="flex items-center justify-between mb-6">
        <h2 class="font-playfair text-2xl font-bold">Rooms</h2>
        <a href="{{ route('admin.rooms.create') }}" class="gold-btn">New Room</a>
    </div>

    <table class="w-full">
        <thead><tr><th>No</th><th>Type</th><th>Number</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($rooms as $room)
                <tr>
                    <td>{{ $room->id }}</td>
                    <td>{{ $room->roomType->name ?? '-' }}</td>
                    <td>{{ $room->room_number }}</td>
                    <td>{{ $room->status }}</td>
                    <td>
                        <a href="{{ route('admin.rooms.edit', $room) }}">Edit</a>
                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button>Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $rooms->links() }}
</x-hotel-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Daftar Kamar') }}</h2>
            <a href="{{ route('admin.rooms.create') }}"
                class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition">Tambah Kamar</a>
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
                @if ($rooms->isEmpty())
                    <p class="text-center py-12 text-gray-500">Belum ada kamar.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Kamar</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Tipe</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($rooms as $room)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $room->room_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $room->roomType->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $room->status === 'available' ? 'bg-green-100 text-green-800' : ($room->status === 'occupied' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($room->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                            <a href="{{ route('admin.rooms.edit', $room) }}"
                                                class="text-amber-600 hover:text-amber-700 font-semibold">Edit</a>
                                            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-700 font-semibold"
                                                    onclick="return confirm('Hapus kamar?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $rooms->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
