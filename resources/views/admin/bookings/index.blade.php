<x-hotel-app-layout>
    <x-slot name="pageTitle">Bookings</x-slot>

    <h2 class="font-playfair text-2xl font-bold">Bookings</h2>

    <table class="w-full mt-4">
        <thead><tr><th>Invoice</th><th>Guest</th><th>Room</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($bookings as $b)
                <tr>
                    <td>{{ $b->invoice_number }}</td>
                    <td>{{ $b->guest->user->name ?? '-' }}</td>
                    <td>{{ $b->room->roomType->name ?? '-' }}</td>
                    <td>{{ $b->status }}</td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $b) }}">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $bookings->links() }}
</x-hotel-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Booking Kamar') }}
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

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6 mb-8 flex justify-between items-center">
                <div class="flex space-x-2">
                    <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ !request('status') ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Semua
                    </a>
                    <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request('status') === 'pending' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Pending
                    </a>
                    <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request('status') === 'confirmed' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Confirmed
                    </a>
                    <a href="{{ route('admin.bookings.index', ['status' => 'checked_in']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request('status') === 'checked_in' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Checked-In
                    </a>
                    <a href="{{ route('admin.bookings.index', ['status' => 'checked_out']) }}" class="px-4 py-2 text-sm font-semibold rounded-lg {{ request('status') === 'checked_out' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Checked-Out
                    </a>
                </div>
            </div>

            <!-- Booking List Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-8">
                @if($bookings->isEmpty())
                    <p class="text-center py-12 text-gray-500">Tidak ada data booking.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Invoice</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tamu</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kamar & Tipe</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Menginap</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total & Pembayaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Booking</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($bookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $booking->invoice_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->guest->user->name }}</div>
                                            <div class="text-xs text-gray-500">Telp: {{ $booking->guest->phone ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->room->roomType->name }}</div>
                                            <div class="text-xs text-gray-500">Kamar No: {{ $booking->room->room_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $booking->check_in->format('d M Y') }} - {{ $booking->check_out->format('d M Y') }}
                                            <div class="text-xs text-gray-400">({{ $booking->total_nights }} malam)</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ format_rupiah($booking->total_price) }}</div>
                                            <div class="text-xs">
                                                @if($booking->payment)
                                                    <span class="px-2 py-0.5 inline-flex text-xxs font-semibold rounded {{ $booking->payment->status_badge }}">
                                                        {{ strtoupper($booking->payment->payment_status) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">Belum Ada Tagihan</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status_badge }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-y-1">
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs transition mr-1">
                                                        Konfirmasi
                                                    </button>
                                                </form>
                                            @endif

                                            @if($booking->status === 'confirmed')
                                                <form action="{{ route('admin.bookings.checkin', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs transition mr-1">
                                                        Check-In
                                                    </button>
                                                </form>
                                            @endif

                                            @if($booking->status === 'checked_in')
                                                <form action="{{ route('admin.bookings.checkout', $booking) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs transition">
                                                        Check-Out
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
