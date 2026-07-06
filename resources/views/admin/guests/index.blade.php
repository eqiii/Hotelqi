<x-hotel-app-layout>
    <x-slot name="pageTitle">Guests</x-slot>

    <h2 class="font-playfair text-2xl font-bold">Guests</h2>

    <table class="w-full mt-4">
        <thead><tr><th>Name</th><th>Email</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($guests as $g)
                <tr>
                    <td>{{ $g->user->name ?? '-' }}</td>
                    <td>{{ $g->user->email ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.guests.show', $g) }}">View</a>
                        <a href="{{ route('admin.guests.edit', $g) }}">Edit</a>
                        <form action="{{ route('admin.guests.destroy', $g) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button>Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $guests->links() }}
</x-hotel-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Tamu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-lg border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                @if ($guests->isEmpty())
                    <p class="text-center py-12 text-gray-500">Belum ada tamu terdaftar.</p>
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
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Telepon</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Booking</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($guests as $guest)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $guest->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $guest->user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $guest->phone ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $guest->bookings()->count() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('admin.guests.show', $guest) }}"
                                                class="text-amber-600 hover:text-amber-700 font-semibold">Lihat</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $guests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
