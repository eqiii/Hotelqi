<x-hotel-app-layout>
    <x-slot name="pageTitle">Pesanan Restoran Saya</x-slot>

    <div class="card-hotel p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Riwayat Pesanan Restoran</h3>

        @if ($orders->isEmpty())
            <div class="text-center py-12 text-gray-500">Belum ada pesanan restoran.</div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="rounded-3xl border border-gray-200 p-6 hover:shadow-lg transition">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-500">No. Pesanan</p>
                                <h4 class="text-lg font-semibold text-gray-900">#{{ $order->id }}</h4>
                            </div>
                            <div>
                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $order->order_status === 'confirmed' ? 'bg-blue-100 text-blue-800' : ($order->order_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ str_replace('_', ' ', ucfirst($order->order_status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-gray-600">
                            <div>
                                <p class="font-medium text-gray-900">Invoice</p>
                                <p class="mt-1">{{ $order->invoice_number }}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Total Harga</p>
                                <p class="mt-1">{{ format_rupiah($order->total) }}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Serving</p>
                                <p class="mt-1">{{ $order->serve_type === 'scheduled' ? 'Scheduled' : 'Now' }}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Dining</p>
                                <p class="mt-1">{{ $order->dining_type === 'room_service' ? 'Room Service' : 'Eat at Restaurant' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('user.restaurant.orders.show', $order) }}"
                                class="inline-flex bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold uppercase tracking-widest px-4 py-2 rounded-xl transition">Lihat
                                Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">{{ $orders->links() }}</div>
        @endif
    </div>
</x-hotel-app-layout>
