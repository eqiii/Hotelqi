<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Pesanan Restoran Saya') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-8">
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
                                            class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                    <div>
                                        <p class="font-medium text-gray-900">Total Harga</p>
                                        <p class="mt-1">{{ format_rupiah($order->total_price) }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Booking Terkait</p>
                                        <p class="mt-1">
                                            {{ $order->booking ? $order->booking->invoice_number : 'Tidak terkait' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Tanggal</p>
                                        <p class="mt-1">{{ $order->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <a href="{{ route('restaurant.orders.show', $order) }}"
                                        class="inline-flex bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold uppercase tracking-widest px-4 py-2 rounded-xl transition">Lihat
                                        Detail</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
