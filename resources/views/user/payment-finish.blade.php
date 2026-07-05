<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Pembayaran - {{ hotel_name() }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body style="background: linear-gradient(135deg, #1a1208 0%, #2d1f0a 100%); min-height: 100vh;"
      class="flex items-center justify-center p-6">

    <div class="w-full max-w-lg">
        {{-- Card --}}
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl">

            {{-- Header --}}
            <div style="background: linear-gradient(135deg, #1a1208 0%, #3d2a0e 100%);" class="p-8 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 rounded-full bg-amber-600 flex items-center justify-center">
                        <span class="text-white font-bold text-lg font-playfair">H</span>
                    </div>
                    <span class="text-white font-playfair text-xl font-bold">{{ hotel_name() }}</span>
                </a>

                @php
                    $isSuccess = in_array($status ?? '', ['settlement', 'capture', 'success']) || ($booking && $booking->payment?->isPaid());
                    $isPending = in_array($status ?? '', ['pending', 'challenge']);
                    $isFailed  = in_array($status ?? '', ['cancel', 'deny', 'expire', 'failed']) && !$isSuccess;
                @endphp

                @if($isSuccess)
                    <div class="w-20 h-20 mx-auto bg-green-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="font-playfair text-2xl font-bold text-white mb-1">Pembayaran Berhasil!</h2>
                    <p class="text-amber-300 text-sm">Terima kasih atas kepercayaan Anda</p>

                @elseif($isPending)
                    <div class="w-20 h-20 mx-auto bg-yellow-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="font-playfair text-2xl font-bold text-white mb-1">Menunggu Pembayaran</h2>
                    <p class="text-amber-300 text-sm">Transaksi Anda sedang diproses</p>

                @else
                    <div class="w-20 h-20 mx-auto bg-red-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h2 class="font-playfair text-2xl font-bold text-white mb-1">Pembayaran Gagal</h2>
                    <p class="text-amber-300 text-sm">Silakan coba lagi</p>
                @endif
            </div>

            {{-- Body --}}
            <div class="p-8">
                @if($orderId)
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Order ID</p>
                        <p class="font-mono text-gray-800 font-semibold">{{ $orderId }}</p>
                    </div>
                @endif

                @if($booking)
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Kamar</span>
                            <span class="font-semibold text-gray-800">{{ $booking->room?->roomType?->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Check In</span>
                            <span class="font-semibold text-gray-800">{{ $booking->check_in?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Check Out</span>
                            <span class="font-semibold text-gray-800">{{ $booking->check_out?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm border-t pt-3">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="font-bold text-amber-600">{{ format_rupiah($booking->total_price) }}</span>
                        </div>
                    </div>
                @endif

                @if($isSuccess)
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                        <p class="text-sm text-green-700 leading-relaxed">
                            ✅ Booking Anda telah dikonfirmasi. Silakan tunjukkan nomor invoice saat check-in. Tim kami akan menghubungi Anda jika diperlukan.
                        </p>
                    </div>
                @elseif($isPending)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                        <p class="text-sm text-yellow-700 leading-relaxed">
                            ⏳ Pembayaran Anda sedang diverifikasi. Anda akan menerima konfirmasi melalui email setelah pembayaran dikonfirmasi.
                        </p>
                    </div>
                @else
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <p class="text-sm text-red-700 leading-relaxed">
                            ❌ Pembayaran tidak berhasil. Silakan coba lagi atau pilih metode pembayaran yang berbeda.
                        </p>
                    </div>
                @endif

                <div class="flex flex-col gap-3">
                    @auth
                        <a href="{{ route('user.booking.history') }}"
                           style="background: linear-gradient(135deg, #c9a96e 0%, #a87a3a 100%);"
                           class="w-full py-3 rounded-xl text-white font-semibold text-center text-sm tracking-wider hover:opacity-90 transition">
                            Lihat Riwayat Booking
                        </a>
                        <a href="{{ route('user.dashboard') }}"
                           class="w-full py-3 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-center text-sm tracking-wider hover:border-amber-400 hover:text-amber-700 transition">
                            Kembali ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('home') }}"
                           style="background: linear-gradient(135deg, #c9a96e 0%, #a87a3a 100%);"
                           class="w-full py-3 rounded-xl text-white font-semibold text-center text-sm tracking-wider hover:opacity-90 transition">
                            Kembali ke Beranda
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

</body>
</html>
