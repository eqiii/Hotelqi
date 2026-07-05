<x-hotel-app-layout>
    <x-slot name="pageTitle">Riwayat Booking</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="font-playfair text-2xl font-bold text-gray-800">Riwayat Booking</h2>
            <p class="text-gray-500 text-sm mt-1">Semua riwayat pemesanan kamar Anda</p>
        </div>
        <a href="{{ route('rooms') }}"
           class="gold-btn px-5 py-2.5 rounded-xl font-semibold text-sm tracking-wider uppercase inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Booking Baru
        </a>
    </div>

    <div class="card-hotel overflow-hidden">
        @if($bookings->isEmpty())
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto bg-amber-50 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="font-playfair text-xl font-bold text-gray-700 mb-2">Belum Ada Booking</h3>
                <p class="text-gray-500 text-sm mb-6">Anda belum pernah melakukan pemesanan kamar.</p>
                <a href="{{ route('rooms') }}"
                   class="gold-btn inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm">
                    Mulai Booking
                </a>
            </div>
        @else
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="background: #1a1208;">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">No. Invoice</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Kamar</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Tanggal Menginap</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-amber-50/50 transition">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-700">{{ $booking->invoice_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-800">{{ $booking->room->roomType->name }}</p>
                                    <p class="text-xs text-gray-500">Kamar No. {{ $booking->room->room_number }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">{{ $booking->check_in->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">s/d {{ $booking->check_out->format('d M Y') }}</p>
                                    <p class="text-xs text-amber-600 font-medium">{{ $booking->total_nights }} malam</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-800">{{ format_rupiah($booking->total_price) }}</p>
                                    @if($booking->payment)
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                            {{ $booking->payment->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($booking->payment->payment_status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ strtoupper($booking->payment->payment_status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ match($booking->status) {
                                            'pending'     => 'bg-yellow-100 text-yellow-700',
                                            'confirmed'   => 'bg-blue-100 text-blue-700',
                                            'checked_in'  => 'bg-green-100 text-green-700',
                                            'checked_out' => 'bg-gray-100 text-gray-700',
                                            'cancelled'   => 'bg-red-100 text-red-700',
                                            default       => 'bg-gray-100 text-gray-700',
                                        } }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($booking->status === 'pending' || ($booking->payment && $booking->payment->payment_status === 'unpaid'))
                                        <a href="{{ route('user.booking.payment', $booking) }}"
                                           class="gold-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            Bayar
                                        </a>
                                    @elseif($booking->status === 'checked_out')
                                        <span class="text-xs text-gray-400 italic">Selesai</span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($bookings as $booking)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <p class="text-xs text-gray-500 font-medium">{{ $booking->invoice_number }}</p>
                                <p class="font-semibold text-gray-800">{{ $booking->room->roomType->name }}</p>
                                <p class="text-xs text-gray-500">Kamar No. {{ $booking->room->room_number }}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ match($booking->status) {
                                    'pending'     => 'bg-yellow-100 text-yellow-700',
                                    'confirmed'   => 'bg-blue-100 text-blue-700',
                                    'checked_in'  => 'bg-green-100 text-green-700',
                                    'checked_out' => 'bg-gray-100 text-gray-700',
                                    'cancelled'   => 'bg-red-100 text-red-700',
                                    default       => 'bg-gray-100 text-gray-700',
                                } }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500">{{ $booking->check_in->format('d M') }} - {{ $booking->check_out->format('d M Y') }}</p>
                                <p class="font-bold text-gray-800">{{ format_rupiah($booking->total_price) }}</p>
                            </div>
                            @if($booking->status === 'pending' || ($booking->payment && $booking->payment->payment_status === 'unpaid'))
                                <a href="{{ route('user.booking.payment', $booking) }}"
                                   class="gold-btn px-4 py-2 rounded-lg text-xs font-semibold">
                                    Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-hotel-app-layout>
