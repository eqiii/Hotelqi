@extends('layouts.admin')

@section('pageTitle')
    Detail Pesanan Restoran
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card-hotel p-8">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-8">
                    <div>
                        <p class="text-sm text-gray-500">No. Pesanan</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $restaurantOrder->order_number_display }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $restaurantOrder->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $restaurantOrder->status_badge }}">
                            {{ $restaurantOrder->status_label }}
                        </span>
                    </div>
                </div>

                @if (session('status'))
                    <div class="mb-6 rounded-lg bg-green-100 border border-green-200 px-5 py-4 text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="rounded-3xl border border-gray-200 p-6 bg-gray-50">
                        <p class="text-sm font-semibold text-gray-900 mb-3">Informasi Pelanggan</p>
                        <p class="text-sm text-gray-600">Nama: <span class="font-medium">{{ $restaurantOrder->guest?->user?->name ?? $restaurantOrder->guest_name ?? '-' }}</span></p>
                        <p class="text-sm text-gray-600">Email: {{ $restaurantOrder->guest?->user?->email ?? '-' }}</p>
                        <p class="text-sm text-gray-600">Phone: {{ $restaurantOrder->guest?->phone ?? '-' }}</p>
                    </div>

                    <div class="rounded-3xl border border-gray-200 p-6 bg-gray-50">
                        <p class="text-sm font-semibold text-gray-900 mb-3">Detail Pesanan</p>
                        <p class="text-sm text-gray-600">Invoice: {{ $restaurantOrder->invoice_number }}</p>
                        <p class="text-sm text-gray-600">Tipe: {{ $restaurantOrder->delivery_label }}</p>
                        @if ($restaurantOrder->room_number)
                            <p class="text-sm text-gray-600">Room: {{ $restaurantOrder->room_number }}</p>
                        @endif
                        @if ($restaurantOrder->guest_name)
                            <p class="text-sm text-gray-600">Guest Name: {{ $restaurantOrder->guest_name }}</p>
                        @endif
                        @if ($restaurantOrder->notes)
                            <p class="text-sm text-gray-600">Notes: {{ $restaurantOrder->notes }}</p>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Menu</h3>
                    <div class="space-y-4">
                        @foreach ($restaurantOrder->details as $detail)
                            <div class="grid grid-cols-2 gap-4 items-center">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $detail->menu->name ?? 'Menu #'.$detail->restaurant_menu_id }}</p>
                                    <p class="text-sm text-gray-500">{{ $detail->quantity }} x {{ format_rupiah($detail->price) }}</p>
                                </div>
                                <div class="text-right text-gray-900 font-semibold">
                                    {{ format_rupiah($detail->subtotal) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 p-6 bg-gray-50 mb-8">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-gray-900 font-semibold">{{ format_rupiah($restaurantOrder->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-gray-600">Tax (10%)</span>
                        <span class="text-gray-900 font-semibold">{{ format_rupiah($restaurantOrder->tax) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <span class="text-gray-600">Total Bayar</span>
                        <span class="text-amber-600 font-bold text-xl">{{ format_rupiah($restaurantOrder->total) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-gray-600">Pembayaran</span>
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                            {{ $restaurantOrder->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($restaurantOrder->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $restaurantOrder->payment_status_label }}
                        </span>
                    </div>
                </div>

                {{-- Update Status Form --}}
                <div class="rounded-3xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status Pesanan</h3>
                    <form action="{{ route('admin.restaurant-orders.update-status', $restaurantOrder) }}" method="POST" class="flex items-end gap-4">
                        @csrf
                        @method('PATCH')
                        <div class="flex-1">
                            <label for="order_status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                            <select name="order_status" id="order_status"
                                class="w-full rounded-xl border-gray-300 focus:border-amber-500 focus:ring-amber-500">
                                <option value="pending" {{ $restaurantOrder->order_status === 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="confirmed" {{ $restaurantOrder->order_status === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="cooking" {{ $restaurantOrder->order_status === 'cooking' ? 'selected' : '' }}>Sedang Dimasak</option>
                                <option value="ready" {{ $restaurantOrder->order_status === 'ready' ? 'selected' : '' }}>Siap Disajikan</option>
                                <option value="delivering" {{ $restaurantOrder->order_status === 'delivering' ? 'selected' : '' }}>Sedang Diantar</option>
                                <option value="completed" {{ $restaurantOrder->order_status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $restaurantOrder->order_status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="gold-btn px-6 py-2.5 rounded-xl text-sm font-semibold">
                            Update Status
                        </button>
                    </form>
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('admin.restaurant-orders.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg transition text-sm">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
