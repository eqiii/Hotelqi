<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tamu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-gray-900">{{ $guest->user->name }}</h3>
                        <p class="text-sm text-gray-600">Email: {{ $guest->user->email }}</p>
                        <p class="text-sm text-gray-600">Telepon: {{ $guest->phone ?? '-' }}</p>
                        <p class="text-sm text-gray-600">Tanggal Daftar: {{ $guest->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="lg:col-span-2">
                        <h4 class="text-base font-semibold text-gray-800 mb-4">Riwayat Booking</h4>
                        @if ($guest->bookings->isEmpty())
                            <p class="text-sm text-gray-500">Belum ada riwayat booking.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                Invoice</th>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                Kamar</th>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th
                                                class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($guest->bookings as $booking)
                                            <tr>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    {{ $booking->invoice_number }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    {{ $booking->room->roomType->name ?? '-' }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    {{ $booking->check_in->format('d M Y') }} -
                                                    {{ $booking->check_out->format('d M Y') }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    {{ ucfirst($booking->status) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
