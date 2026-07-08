<x-hotel-app-layout>
    <x-slot name="pageTitle">Detail Pesanan Restoran</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="card-hotel p-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-8">
                <div>
                    <p class="text-sm text-gray-500">No. Pesanan</p>
                    <h3 class="text-2xl font-bold text-gray-900">#{{ $restaurantOrder->id }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $restaurantOrder->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <span
                        class="inline-flex px-4 py-2 rounded-full text-sm font-semibold {{ $restaurantOrder->order_status === 'confirmed' ? 'bg-blue-100 text-blue-800' : ($restaurantOrder->order_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ str_replace('_', ' ', ucfirst($restaurantOrder->order_status)) }}
                    </span>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-gray-200 p-6 bg-gray-50">
                    <p class="text-sm font-semibold text-gray-900">Detail Pesanan</p>
                    <p class="mt-2 text-sm text-gray-600">Invoice: {{ $restaurantOrder->invoice_number }}</p>
                    <p class="text-sm text-gray-600">Serving: {{ $restaurantOrder->serve_type === 'scheduled' ? 'Scheduled' : 'Now' }}</p>
                    <p class="text-sm text-gray-600">Dining: {{ $restaurantOrder->dining_type === 'room_service' ? 'Room Service' : 'Eat at Restaurant' }}</p>
                    @if ($restaurantOrder->guest_name)
                        <p class="text-sm text-gray-600">Guest Name: {{ $restaurantOrder->guest_name }}</p>
                    @endif
                    @if ($restaurantOrder->room_number)
                        <p class="text-sm text-gray-600">Room Number: {{ $restaurantOrder->room_number }}</p>
                    @endif
                    @if ($restaurantOrder->booking)
                        <p class="text-sm text-gray-600">Booking: {{ $restaurantOrder->booking->invoice_number }}</p>
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
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-gray-900 font-semibold">{{ format_rupiah($restaurantOrder->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-gray-600">Tax</span>
                        <span class="text-gray-900 font-semibold">{{ format_rupiah($restaurantOrder->tax) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-gray-600">Total Bayar</span>
                        <span class="text-amber-600 font-bold text-xl">{{ format_rupiah($restaurantOrder->total) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('user.restaurant.orders') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg transition text-sm">Kembali
                    ke Pesanan</a>
            </div>
        </div>
    </div>
</x-hotel-app-layout>
