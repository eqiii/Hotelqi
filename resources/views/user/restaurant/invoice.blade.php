<x-hotel-app-layout>
    <x-slot name="pageTitle">Invoice Pesanan Restoran</x-slot>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #invoice-area,
            #invoice-area * {
                visibility: visible;
            }
            #invoice-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 1.5rem;
                background: white;
            }
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            .print-break-inside {
                break-inside: avoid;
            }
            @page {
                margin: 1cm;
            }
        }
        .print-only {
            display: none;
        }
    </style>

    <div class="max-w-4xl mx-auto">
        {{-- Tombol Aksi --}}
        <div class="no-print mb-6 flex flex-wrap gap-3">
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Download / Print Invoice
            </button>
            <a href="{{ route('user.restaurant.orders.show', $restaurantOrder) }}"
                class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                ← Kembali ke Detail
            </a>
        </div>

        {{-- Area Invoice --}}
        <div id="invoice-area" class="card-hotel p-8 md:p-12 bg-white">
            {{-- Kop --}}
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6 border-b border-gray-300 pb-8 mb-8">
                <div>
                    @if ($hotelProfile && $hotelProfile->logo)
                        <img src="{{ $hotelProfile->logo_url }}" alt="{{ $hotelProfile->name ?? 'Hotel' }}" class="h-16 mb-3 object-contain">
                    @else
                        <div class="text-3xl font-bold text-amber-700 mb-1 font-playfair">{{ $hotelProfile->name ?? config('app.name', 'Hotel Eqi') }}</div>
                    @endif
                    <h1 class="text-2xl font-bold text-gray-900 font-playfair mt-2">Restaurant Invoice</h1>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Invoice Number</p>
                    <p class="text-lg font-bold text-gray-900">{{ $restaurantOrder->invoice_number }}</p>
                    <p class="text-sm text-gray-500 mt-2">Tanggal</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $restaurantOrder->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            {{-- Informasi Hotel + Customer --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Hotel</h3>
                    <p class="font-bold text-gray-900">{{ $hotelProfile->name ?? config('app.name', 'Hotel Eqi') }}</p>
                    @if ($hotelProfile && $hotelProfile->address)
                        <p class="text-sm text-gray-600 mt-1">{{ $hotelProfile->address }}</p>
                    @endif
                    @if ($hotelProfile && $hotelProfile->phone)
                        <p class="text-sm text-gray-600">Tel: {{ $hotelProfile->phone }}</p>
                    @endif
                </div>
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Customer</h3>
                    <p class="font-bold text-gray-900">{{ $restaurantOrder->guest_name ?? auth()->user()->name ?? '-' }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        Tipe Layanan:
                        <span class="font-semibold text-gray-800">{{ $restaurantOrder->delivery_label }}</span>
                    </p>
                    @if ($restaurantOrder->room_number)
                        <p class="text-sm text-gray-600">No. Kamar: <span class="font-semibold text-gray-800">{{ $restaurantOrder->room_number }}</span></p>
                    @endif
                    @if ($restaurantOrder->payment_method)
                        <p class="text-sm text-gray-600">Metode Bayar: <span class="font-semibold text-gray-800">{{ ucfirst($restaurantOrder->payment_method) }}</span></p>
                    @endif
                </div>
            </div>

            {{-- Detail Pesanan --}}
            <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Pesanan</h3>
            <div class="overflow-x-auto mb-8">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-300">
                            <th class="text-left py-3 px-2 font-semibold text-gray-700">Menu</th>
                            <th class="text-center py-3 px-2 font-semibold text-gray-700 w-20">Qty</th>
                            <th class="text-right py-3 px-2 font-semibold text-gray-700 w-28">Harga</th>
                            <th class="text-right py-3 px-2 font-semibold text-gray-700 w-28">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($restaurantOrder->details as $detail)
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-2 text-gray-800">{{ $detail->menu->name ?? 'Menu' }}</td>
                                <td class="py-3 px-2 text-center text-gray-800">{{ $detail->quantity }}</td>
                                <td class="py-3 px-2 text-right text-gray-800">{{ format_rupiah($detail->price) }}</td>
                                <td class="py-3 px-2 text-right font-semibold text-gray-800">{{ format_rupiah($detail->subtotal) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500">Tidak ada detail pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Ringkasan --}}
            <div class="flex justify-end mb-8">
                <div class="w-full md:w-72 space-y-2">
                    <div class="flex justify-between py-2 text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-semibold text-gray-800">{{ format_rupiah($restaurantOrder->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between py-2 text-sm text-gray-600 border-t border-gray-200">
                        <span>Tax</span>
                        <span class="font-semibold text-gray-800">{{ format_rupiah($restaurantOrder->tax) }}</span>
                    </div>
                    <div class="flex justify-between py-3 text-base font-bold text-gray-900 border-t-2 border-gray-300">
                        <span>Total Bayar</span>
                        <span class="text-amber-600 text-xl">{{ format_rupiah($restaurantOrder->total) }}</span>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="border-t border-gray-300 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Status Pembayaran</h4>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold
                        {{ $restaurantOrder->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        <span class="w-2 h-2 rounded-full {{ $restaurantOrder->payment_status === 'paid' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                        {{ $restaurantOrder->payment_status_label }}
                    </span>
                    @if ($restaurantOrder->paid_at)
                        <p class="text-sm text-gray-500 mt-2">Lunas pada: {{ $restaurantOrder->paid_at->format('d M Y H:i') }}</p>
                    @endif
                </div>
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Status Pesanan</h4>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold {{ $restaurantOrder->status_badge }}">
                        <span class="w-2 h-2 rounded-full
                            {{ in_array($restaurantOrder->order_status, ['completed', 'ready']) ? 'bg-green-500' : 'bg-amber-500' }}"></span>
                        {{ $restaurantOrder->status_label }}
                    </span>
                    @if ($restaurantOrder->order_status === 'confirmed' || $restaurantOrder->order_status === 'completed')
                        <p class="text-sm text-gray-500 mt-2">Pesanan terkonfirmasi.</p>
                    @endif
                </div>
            </div>

            {{-- Footer --}}
            <div class="mt-10 pt-6 border-t border-gray-200 text-center text-xs text-gray-400">
                <p>{{ $hotelProfile->name ?? config('app.name', 'Hotel Eqi') }} — Restaurant Invoice</p>
                <p class="mt-1">Terima kasih atas kunjungan Anda.</p>
            </div>
        </div>
    </div>
</x-hotel-app-layout>
