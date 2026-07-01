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

                <!-- Payment Method / Transfer Instructions -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                        <span class="text-xl mr-2">💳</span> Instruksi Pembayaran Transfer Bank
                    </h4>
                    <p class="text-sm text-gray-600 mb-4">Silakan transfer pembayaran Anda ke salah satu rekening berikut:</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-white p-4 rounded border border-gray-200">
                            <p class="text-xs font-semibold text-gray-400">BANK BCA</p>
                            <p class="text-lg font-bold text-gray-800 tracking-wider">123-45678-90</p>
                            <p class="text-xs text-gray-500">a/n PT. Hotel Mewah Indonesia</p>
                        </div>
                        <div class="bg-white p-4 rounded border border-gray-200">
                            <p class="text-xs font-semibold text-gray-400">BANK MANDIRI</p>
                            <p class="text-lg font-bold text-gray-800 tracking-wider">987-654-321-0</p>
                            <p class="text-xs text-gray-500">a/n PT. Hotel Mewah Indonesia</p>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 space-y-1">
                        <p class="font-semibold text-gray-700">Penting:</p>
                        <p>1. Transfer tepat sesuai nominal total tagihan: <span class="font-bold text-amber-600">{{ format_rupiah($booking->total_price) }}</span>.</p>
                        <p>2. Setelah melakukan transfer, silakan konfirmasikan pembayaran Anda ke Resepsionis kami dengan melampirkan bukti transfer atau menunggu konfirmasi otomatis dari sistem Admin kami.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('user.booking.history') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg transition mr-4 text-sm">
                        Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
