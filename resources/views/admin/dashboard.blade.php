<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Status -->
            @if(session('status'))
                <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-lg border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Metric Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Rooms -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">
                    <div class="text-3xl mr-4">🏨</div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold uppercase">Total Kamar</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalRooms }}</p>
                        <div class="text-xs text-gray-400 mt-1">
                            <span class="text-green-600 font-semibold">{{ $availableRooms }} Tersedia</span>
                        </div>
                    </div>
                </div>

                <!-- Active Bookings -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">
                    <div class="text-3xl mr-4">📅</div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold uppercase">Booking Aktif</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $activeBookings }}</p>
                        <div class="text-xs text-gray-400 mt-1">
                            <span class="text-blue-600 font-semibold">{{ $occupiedRooms }} Kamar Terisi</span>
                        </div>
                    </div>
                </div>

                <!-- Total Guests -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">
                    <div class="text-3xl mr-4">👤</div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold uppercase">Total Tamu</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalGuests }}</p>
                        <div class="text-xs text-gray-400 mt-1">
                            <span>Tamu Terdaftar</span>
                        </div>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">
                    <div class="text-3xl mr-4">💰</div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold uppercase">Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-800">{{ format_rupiah($totalRevenue) }}</p>
                        <div class="text-xs text-gray-400 mt-1">
                            <span>Pembayaran Lunas</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Panels -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Bookings Management Panel -->
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-100 lg:col-span-2">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Booking Terbaru</h3>
                        <a href="{{ route('admin.bookings.index') }}" class="text-amber-600 hover:text-amber-700 font-semibold text-sm transition">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($latestBookings->isEmpty())
                        <p class="text-center py-6 text-gray-500 text-sm">Belum ada pemesanan terbaru.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tamu</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kamar</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($latestBookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $booking->guest->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $booking->guest->phone }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $booking->room->roomType->name }}</div>
                                                <div class="text-xs text-gray-500">No. {{ $booking->room->room_number }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $booking->check_in->format('d M') }} - {{ $booking->check_out->format('d M') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status_badge }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Room Status breakdown -->
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Status Kamar</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-600 flex items-center">
                                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> Tersedia
                            </span>
                            <span class="font-bold text-gray-800">{{ $availableRooms }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-600 flex items-center">
                                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span> Terisi / Occupied
                            </span>
                            <span class="font-bold text-gray-800">{{ $occupiedRooms }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-600 flex items-center">
                                <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span> Perawatan / Maintenance
                            </span>
                            <span class="font-bold text-gray-800">{{ $maintenanceRooms }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
