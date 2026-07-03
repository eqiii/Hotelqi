<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Pembayaran Selesai') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-8 text-center">
                <div class="text-amber-600 text-6xl mb-6">✅</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Pembayaran sudah diproses</h3>
                <p class="text-gray-600 mb-8">Terima kasih, pembayaran Anda telah diterima. Silakan kembali ke riwayat
                    pemesanan untuk melihat status terkini.</p>
                <a href="{{ route('user.booking.history') }}"
                    class="inline-flex bg-amber-600 hover:bg-amber-700 text-white uppercase tracking-widest font-semibold py-3 px-8 rounded-lg transition">Kembali
                    ke Riwayat</a>
            </div>
        </div>
    </div>
</x-app-layout>
