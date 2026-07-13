<x-hotel-app-layout>
    <x-slot name="pageTitle">Detail Pesanan Restoran</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="card-hotel p-8">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-8">
                <div>
                    <p class="text-sm text-gray-500">No. Pesanan</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $restaurantOrder->order_number_display }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $restaurantOrder->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold {{ $restaurantOrder->status_badge }}">
                        {{ $restaurantOrder->status_label }}
                    </span>
                    <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold
                        {{ $restaurantOrder->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $restaurantOrder->payment_status_label }}
                    </span>
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="rounded-3xl border border-gray-200 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Status Pesanan</h3>

                @php
                    $isDineIn = $restaurantOrder->dining_type === 'dine_in';
                    $currentStatus = $restaurantOrder->order_status;

                    // Define timeline steps based on delivery type
                    if ($isDineIn) {
                        $steps = [
                            ['key' => 'pending', 'label' => 'Menunggu Konfirmasi', 'icon' => '🟡'],
                            ['key' => 'confirmed', 'label' => 'Sedang Diproses', 'icon' => '🟠'],
                            ['key' => 'cooking', 'label' => 'Sedang Dimasak', 'icon' => '🔥'],
                            ['key' => 'ready', 'label' => 'Siap Disajikan', 'icon' => '🔵'],
                            ['key' => 'completed', 'label' => 'Selesai', 'icon' => '✅'],
                        ];
                    } else {
                        $steps = [
                            ['key' => 'pending', 'label' => 'Menunggu Konfirmasi', 'icon' => '🟡'],
                            ['key' => 'confirmed', 'label' => 'Sedang Diproses', 'icon' => '🟠'],
                            ['key' => 'cooking', 'label' => 'Sedang Dimasak', 'icon' => '🔥'],
                            ['key' => 'delivering', 'label' => 'Sedang Diantar', 'icon' => '🚚'],
                            ['key' => 'completed', 'label' => 'Selesai', 'icon' => '✅'],
                        ];
                    }

                    $statusOrder = array_column($steps, 'key');
                    $currentIndex = array_search($currentStatus, $statusOrder);
                    $isCancelled = $currentStatus === 'cancelled';
                @endphp

                @if ($isCancelled)
                    <div class="text-center py-6">
                        <span class="text-4xl">❌</span>
                        <p class="mt-2 text-lg font-semibold text-red-600">Pesanan Dibatalkan</p>
                        <p class="text-sm text-gray-500">Pesanan ini telah dibatalkan.</p>
                    </div>
                @else
                    <div class="relative">
                        {{-- Progress Bar --}}
                        <div class="absolute top-5 left-8 right-8 h-1 bg-gray-200 rounded-full">
                            @if ($currentIndex !== false && $currentIndex >= 0)
                                <div class="h-full bg-amber-500 rounded-full transition-all duration-500"
                                    style="width: {{ ($currentIndex / (count($steps) - 1)) * 100 }}%">
                                </div>
                            @endif
                        </div>

                        {{-- Steps --}}
                        <div class="relative flex justify-between">
                            @foreach ($steps as $index => $step)
                                @php
                                    $isCompleted = $currentIndex !== false && $index <= $currentIndex;
                                    $isCurrent = $currentIndex !== false && $index === $currentIndex;
                                @endphp
                                <div class="flex flex-col items-center" style="width: {{ 100 / count($steps) }}%">
                                    <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center text-lg
                                        {{ $isCompleted ? 'bg-amber-500 text-white' : ($isCurrent ? 'bg-amber-200 text-amber-700 border-2 border-amber-500' : 'bg-gray-100 text-gray-400') }}">
                                        {{ $step['icon'] }}
                                    </div>
                                    <p class="mt-2 text-xs font-semibold text-center
                                        {{ $isCompleted ? 'text-amber-600' : ($isCurrent ? 'text-amber-700' : 'text-gray-400') }}">
                                        {{ $step['label'] }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Current Status Message --}}
                    <div class="mt-6 text-center p-4 bg-amber-50 rounded-xl border border-amber-200">
                        @switch($currentStatus)
                            @case('pending')
                                <p class="text-amber-700 font-semibold">🟡 Pesanan Anda sedang menunggu konfirmasi dari restoran.</p>
                                @break
                            @case('confirmed')
                                <p class="text-orange-700 font-semibold">🟠 Pesanan Anda telah dikonfirmasi dan sedang diproses.</p>
                                @break
                            @case('cooking')
                                <p class="text-orange-700 font-semibold">🔥 Chef sedang memasak pesanan Anda!</p>
                                @break
                            @case('ready')
                                <p class="text-blue-700 font-semibold">🔵 Pesanan Anda sudah siap disajikan!</p>
                                @break
                            @case('delivering')
                                <p class="text-purple-700 font-semibold">🚚 Pesanan Anda sedang dalam perjalanan ke kamar!</p>
                                @break
                            @case('completed')
                                <p class="text-green-700 font-semibold">✅ Pesanan selesai. Selamat menikmati!</p>
                                @break
                            @default
                                <p class="text-gray-700 font-semibold">Status: {{ $restaurantOrder->status_label }}</p>
                        @endswitch
                    </div>
                @endif
            </div>

            {{-- Order Details --}}
            <div class="space-y-6">
                <div class="rounded-3xl border border-gray-200 p-6 bg-gray-50">
                    <p class="text-sm font-semibold text-gray-900">Detail Pesanan</p>
                    <p class="mt-2 text-sm text-gray-600">Invoice: {{ $restaurantOrder->invoice_number }}</p>
                    <p class="text-sm text-gray-600">Pengiriman: {{ $restaurantOrder->delivery_label }}</p>
                    @if ($restaurantOrder->guest_name)
                        <p class="text-sm text-gray-600">Nama Tamu: {{ $restaurantOrder->guest_name }}</p>
                    @endif
                    @if ($restaurantOrder->room_number)
                        <p class="text-sm text-gray-600">No. Kamar: {{ $restaurantOrder->room_number }}</p>
                    @endif
                    @if ($restaurantOrder->booking)
                        <p class="text-sm text-gray-600">Booking: {{ $restaurantOrder->booking->invoice_number }}</p>
                    @endif
                    @if ($restaurantOrder->notes)
                        <p class="text-sm text-gray-600">Catatan: {{ $restaurantOrder->notes }}</p>
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
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-gray-600">Status Pembayaran</span>
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                            {{ $restaurantOrder->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $restaurantOrder->payment_status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-between">
                <a href="{{ route('user.restaurant.orders') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                    ← Kembali ke Pesanan
                </a>
                <a href="{{ route('user.restaurant.orders.invoice', $restaurantOrder) }}"
                    class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                    Lihat Invoice
                </a>
            </div>
        </div>
    </div>
</x-hotel-app-layout>
