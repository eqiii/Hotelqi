<x-hotel-app-layout>
    <x-slot name="pageTitle">Pesanan Restoran Saya</x-slot>

    <div class="card-hotel p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Pesanan Restoran Saya</h3>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 border border-green-200 px-5 py-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-100 border border-red-200 px-5 py-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="text-center py-12 text-gray-500">Belum ada pesanan restoran.</div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="rounded-3xl border border-gray-200 p-6 hover:shadow-lg transition">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-500">No. Pesanan</p>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $order->order_number_display }}</h4>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_badge }}">
                                    {{ $order->status_label }}
                                </span>
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $order->payment_status_label }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 space-y-2">
                            @foreach ($order->details as $detail)
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>{{ $detail->menu->name ?? 'Menu' }} <span class="text-gray-400">x{{ $detail->quantity }}</span></span>
                                    <span>{{ format_rupiah($detail->subtotal) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                            <div>
                                <p class="font-medium text-gray-900">Pengiriman</p>
                                <p class="mt-1">{{ $order->delivery_label }}</p>
                            </div>
                            @if ($order->room_number)
                            <div>
                                <p class="font-medium text-gray-900">No. Kamar</p>
                                <p class="mt-1">{{ $order->room_number }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">Total Harga</p>
                                <p class="mt-1 font-semibold text-amber-600">{{ format_rupiah($order->total) }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('user.restaurant.orders.show', $order) }}"
                                class="inline-flex bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold uppercase tracking-widest px-4 py-2 rounded-xl transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $orders->links() }}</div>
        @endif
    </div>
</x-hotel-app-layout>
