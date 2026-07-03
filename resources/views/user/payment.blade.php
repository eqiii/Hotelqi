<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-8">
                <!-- Status Info -->
                <div class="mb-8 border-b pb-6 flex justify-between items-center">
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-widest">No. Invoice</span>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $booking->invoice_number }}</h3>
                    </div>
                    <div>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status_badge }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 border-b pb-6">
                    <div>
                        <h4 class="font-bold text-gray-800 mb-4">Rincian Booking</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><span class="font-semibold text-gray-800">Tipe Kamar:</span> {{ $booking->room->roomType->name }}</p>
                            <p><span class="font-semibold text-gray-800">No. Kamar:</span> {{ $booking->room->room_number }}</p>
                            <p><span class="font-semibold text-gray-800">Check In:</span> {{ $booking->check_in->format('d M Y') }}</p>
                            <p><span class="font-semibold text-gray-800">Check Out:</span> {{ $booking->check_out->format('d M Y') }}</p>
                            <p><span class="font-semibold text-gray-800">Lama Menginap:</span> {{ $booking->total_nights }} malam</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-4">Rincian Biaya</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Harga Dasar / Malam</span>
                                <span>{{ format_rupiah($booking->room->roomType->base_price) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-gray-900 border-t pt-2 mt-2">
                                <span>Total Tagihan</span>
                                <span class="text-amber-600 text-lg">{{ format_rupiah($booking->total_price) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($booking->payment && $booking->payment->payment_status === 'paid')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-semibold text-green-800">Pembayaran Terbayar</h4>
                        <p class="text-sm text-green-700 mt-2">Pembayaran Anda telah berhasil. Terima kasih dan selamat menikmati layanan kami.</p>
                    </div>
                @elseif(isset($snapToken) && $snapToken)
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-semibold text-amber-900 mb-3">Pembayaran Midtrans</h4>
                        <p class="text-sm text-gray-600 mb-4">Klik tombol di bawah untuk melanjutkan pembayaran menggunakan metode yang Anda pilih.</p>
                        <button id="pay-button" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold uppercase tracking-widest px-6 py-3 rounded-lg transition">
                            Bayar Sekarang
                        </button>
                    </div>
                @else
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                        <h4 class="font-bold text-gray-800 mb-4 flex items-center"><span class="text-xl mr-2">💳</span> Pembayaran Midtrans</h4>
                        <p class="text-sm text-gray-600 mb-4">Silakan kembali ke riwayat pemesanan dan mulai ulang proses pembayaran jika diperlukan.</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-4">Ringkasan Pemesanan</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Sub-total</span>
                                <span>{{ format_rupiah($booking->total_price) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-4">Catatan</h4>
                        <p class="text-sm text-gray-600">Jika ada kendala pembayaran, hubungi layanan pelanggan kami atau periksa kembali detail booking Anda.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('user.booking.history') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                        Kembali ke Riwayat
                    </a>
                    @if(isset($snapToken) && $snapToken)
                        <a href="{{ route('payment.finish') }}" class="text-amber-600 hover:text-amber-700 text-sm font-semibold">Lihat hasil pembayaran</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(isset($snapToken) && $snapToken)
        <script src="{{ config('mdtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('mdtrans.client_key') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const payButton = document.getElementById('pay-button');
                if (!payButton) return;

                payButton.addEventListener('click', function (event) {
                    event.preventDefault();

                    window.snap.pay('{{ $snapToken }}', {
                        onSuccess: function(result){
                            window.location.href = '{{ route('payment.finish') }}?order_id=booking-{{ $booking->id }}';
                        },
                        onPending: function(result){
                            window.location.href = '{{ route('payment.finish') }}?order_id=booking-{{ $booking->id }}';
                        },
                        onError: function(result){
                            alert('Pembayaran gagal. Silakan coba lagi.');
                        }
                    });
                });
            });
        </script>
    @endif
</x-app-layout>
