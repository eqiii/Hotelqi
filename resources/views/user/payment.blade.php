<x-hotel-app-layout>
    <x-slot name="pageTitle">Pembayaran Booking</x-slot>

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('user.dashboard') }}" class="hover:text-amber-600 transition">Dashboard</a>
            <span>›</span>
            <a href="{{ route('user.booking.history') }}" class="hover:text-amber-600 transition">Riwayat</a>
            <span>›</span>
            <span class="text-gray-700 font-medium">Pembayaran</span>
        </div>
        <h2 class="font-playfair text-2xl font-bold text-gray-800">Detail Pembayaran</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Invoice Detail --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Invoice Header --}}
            <div class="card-hotel p-6">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold">No. Invoice</p>
                        <h3 class="text-xl font-bold text-gray-800 font-playfair">{{ $booking->invoice_number }}</h3>
                    </div>
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                        {{ match($booking->status) {
                            'pending'     => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                            'confirmed'   => 'bg-blue-100 text-blue-700 border border-blue-200',
                            'checked_in'  => 'bg-green-100 text-green-700 border border-green-200',
                            'checked_out' => 'bg-gray-100 text-gray-700 border border-gray-200',
                            'cancelled'   => 'bg-red-100 text-red-700 border border-red-200',
                            default       => 'bg-gray-100 text-gray-700',
                        } }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>

                {{-- Booking Info --}}
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Tipe Kamar</p>
                            <p class="font-semibold text-gray-800">{{ $booking->room->roomType->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">No. Kamar</p>
                            <p class="font-semibold text-gray-800">{{ $booking->room->room_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Lama Menginap</p>
                            <p class="font-semibold text-gray-800">{{ $booking->total_nights }} malam</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Check In</p>
                            <p class="font-semibold text-gray-800">{{ $booking->check_in->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Check Out</p>
                            <p class="font-semibold text-gray-800">{{ $booking->check_out->format('d M Y') }}</p>
                        </div>
                        @if($booking->notes)
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Catatan</p>
                                <p class="text-sm text-gray-700">{{ $booking->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Price Breakdown --}}
            <div class="card-hotel p-6">
                <h4 class="font-playfair text-lg font-bold text-gray-800 mb-4">Rincian Biaya</h4>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Harga Dasar per Malam</span>
                        <span class="font-medium text-gray-800">{{ format_rupiah($booking->room->roomType->base_price) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Jumlah Malam</span>
                        <span class="font-medium text-gray-800">× {{ $booking->total_nights }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 flex justify-between">
                        <span class="font-bold text-gray-800">Total Tagihan</span>
                        <span class="font-bold text-xl text-amber-600">{{ format_rupiah($booking->total_price) }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment Section --}}
            @if($booking->payment && $booking->payment->payment_status === 'paid')
                <div class="card-hotel p-6 border-green-200 bg-green-50">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-800">Pembayaran Berhasil</h4>
                            <p class="text-xs text-green-700">
                                Dibayar pada {{ $booking->payment->paid_at?->format('d M Y, H:i') ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <p class="text-sm text-green-700">Terima kasih! Booking Anda telah dikonfirmasi. Kami menantikan kedatangan Anda.</p>
                </div>

                @elseif(isset($snapToken) && $snapToken)
                <div class="card-hotel p-6">
                    <h4 class="font-playfair text-lg font-bold text-gray-800 mb-2">Selesaikan Pembayaran</h4>
                    <p class="text-sm text-gray-600 mb-5">Klik tombol di bawah untuk membuka halaman pembayaran Midtrans. Anda dapat membayar dengan kartu kredit, transfer bank, e-wallet, dan lainnya.</p>
                    <button id="pay-button"
                            class="gold-btn w-full py-4 rounded-xl font-bold text-sm tracking-widest uppercase flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Bayar Sekarang — {{ format_rupiah($booking->total_price) }}
                    </button>
                    <p class="text-xs text-center text-gray-400 mt-3">Pembayaran diproses secara aman melalui Midtrans</p>
                </div>
            @else
                <div class="card-hotel p-6 bg-yellow-50 border-yellow-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-yellow-800">Menunggu Pembayaran</h4>
                            <p class="text-sm text-yellow-700">Silakan hubungi staff hotel jika mengalami kendala.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right: Summary & Actions --}}
        <div class="space-y-5">
            {{-- Payment Status --}}
            <div class="card-hotel p-5">
                <h4 class="font-semibold text-gray-700 text-sm uppercase tracking-wider mb-4">Status Pembayaran</h4>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Metode</span>
                        <span class="font-medium text-gray-800 uppercase">
                            {{ $booking->payment?->payment_method ?? '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Status</span>
                        @if($booking->payment)
                            <span class="font-semibold px-2 py-0.5 rounded-full text-xs
                                {{ $booking->payment->payment_status === 'paid' ? 'bg-green-100 text-green-700' :
                                   ($booking->payment->payment_status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ strtoupper($booking->payment->payment_status) }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </div>
                    <div class="flex justify-between text-sm border-t border-gray-100 pt-3">
                        <span class="font-bold text-gray-800">Total</span>
                        <span class="font-bold text-amber-600">{{ format_rupiah($booking->total_price) }}</span>
                    </div>
                </div>
            </div>

            {{-- Need Help --}}
            <div class="card-hotel p-5 bg-gradient-to-br from-amber-50 to-orange-50 border-amber-200">
                <h4 class="font-semibold text-amber-800 mb-2">Butuh Bantuan?</h4>
                <p class="text-xs text-amber-700 leading-relaxed mb-3">
                    Tim layanan pelanggan kami siap membantu Anda 24/7.
                </p>
                <a href="{{ route('faq') }}" class="text-amber-700 hover:text-amber-800 text-xs font-semibold underline">
                    Lihat FAQ →
                </a>
            </div>

            {{-- Navigation --}}
            <a href="{{ route('user.booking.history') }}"
               class="w-full flex items-center justify-center gap-2 py-3 px-4 border-2 border-gray-200 hover:border-amber-400 rounded-xl text-sm font-semibold text-gray-600 hover:text-amber-700 transition">
                ← Kembali ke Riwayat
            </a>
        </div>
    </div>

    {{-- Midtrans Snap JS --}}
    @if(isset($snapToken) && $snapToken)
        <script src="{{ config('mdtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
                data-client-key="{{ config('mdtrans.client_key') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const payButton = document.getElementById('pay-button');
                if (!payButton) return;

                payButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    payButton.disabled = true;
                    payButton.innerHTML = '<span class="animate-spin mr-2">⏳</span> Memproses...';

                    window.snap.pay('{{ $snapToken }}', {
                        onSuccess: function(result) {
                            window.location.href = '{{ route('payment.finish') }}?order_id=booking-{{ $booking->id }}&transaction_status=settlement';
                        },
                        onPending: function(result) {
                            window.location.href = '{{ route('payment.finish') }}?order_id=booking-{{ $booking->id }}&transaction_status=pending';
                        },
                        onError: function(result) {
                            payButton.disabled = false;
                            payButton.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> Bayar Sekarang — {{ format_rupiah($booking->total_price) }}';
                            alert('Pembayaran gagal. Silakan coba lagi.');
                        },
                        onClose: function() {
                            payButton.disabled = false;
                            payButton.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> Bayar Sekarang — {{ format_rupiah($booking->total_price) }}';
                        }
                    });
                });
            });
        </script>
    @endif
</x-hotel-app-layout>
