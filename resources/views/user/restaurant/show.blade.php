<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detail Pesanan Restoran') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-8">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-8">
                    <div>
                        <p class="text-sm text-gray-500">No. Pesanan</p>
                        <h3 class="text-2xl font-bold text-gray-900">#{{ $restaurantOrder->id }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $restaurantOrder->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <span
                            class="inline-flex px-4 py-2 rounded-full text-sm font-semibold {{ $restaurantOrder->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($restaurantOrder->status) }}
                        </span>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-gray-200 p-6 bg-gray-50">
                        <p class="text-sm font-semibold text-gray-900">Detail Booking Terkait</p>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ $restaurantOrder->booking ? $restaurantOrder->booking->invoice_number : 'Tidak terkait dengan booking' }}
                        </p>
                        @if ($restaurantOrder->booking)
                            <p class="text-sm text-gray-600">Kamar:
                                {{ $restaurantOrder->booking->room->roomType->name }}
                                ({{ $restaurantOrder->booking->room->room_number }})</p>
                        @endif
                    </div>

                    <div class="rounded-3xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Menu</h3>
                        <div class="space-y-4">
                            @foreach ($restaurantOrder->details as $detail)
                                <div class="grid grid-cols-2 gap-4 items-center">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $detail->menu->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $detail->quantity }} x
                                            {{ format_rupiah($detail->price) }}</p>
                                    </div>
                                    <div class="text-right text-gray-900 font-semibold">
                                        {{ format_rupiah($detail->subtotal) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-3xl border border-gray-200 p-6 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Bayar</span>
                            <span
                                class="text-amber-600 font-bold text-xl">{{ format_rupiah($restaurantOrder->total_price) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('restaurant.orders') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg transition text-sm">Kembali
                        ke Pesanan</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
