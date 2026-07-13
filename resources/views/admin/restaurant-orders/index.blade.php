@extends('layouts.admin')

@section('pageTitle')
    Restaurant Orders
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card-hotel p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Pesanan Restoran</h3>
                </div>

                @if (session('status'))
                    <div class="mb-6 rounded-lg bg-green-100 border border-green-200 px-5 py-4 text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($orders->isEmpty())
                    <div class="text-center py-12 text-gray-500">
                        Belum ada pesanan restoran.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b text-left">
                                    <th class="py-3 px-2 text-gray-500 font-semibold">Order #</th>
                                    <th class="py-3 px-2 text-gray-500 font-semibold">Customer</th>
                                    <th class="py-3 px-2 text-gray-500 font-semibold">Items</th>
                                    <th class="py-3 px-2 text-gray-500 font-semibold">Type</th>
                                    <th class="py-3 px-2 text-gray-500 font-semibold">Room</th>
                                    <th class="py-3 px-2 text-gray-500 font-semibold">Payment</th>
                                    <th class="py-3 px-2 text-gray-500 font-semibold">Status</th>
                                    <th class="py-3 px-2 text-gray-500 font-semibold">Date</th>
                                    <th class="py-3 px-2 text-gray-500 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-4 px-2">
                                            <span class="font-semibold text-gray-800">{{ $order->order_number_display }}</span>
                                        </td>
                                        <td class="py-4 px-2">
                                            <p class="font-medium text-gray-800">{{ $order->guest?->user?->name ?? $order->guest_name ?? '-' }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->guest?->user?->email ?? '' }}</p>
                                        </td>
                                        <td class="py-4 px-2">
                                            @if ($order->details->count() > 0)
                                                <span class="text-gray-600">{{ $order->details->count() }} item(s)</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-2">
                                            <span class="text-gray-600">{{ $order->delivery_label }}</span>
                                        </td>
                                        <td class="py-4 px-2">
                                            <span class="text-gray-600">{{ $order->room_number ?? '-' }}</span>
                                        </td>
                                        <td class="py-4 px-2">
                                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $order->payment_status_label }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-2">
                                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $order->status_badge }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-2 text-gray-500 text-xs">
                                            {{ $order->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="py-4 px-2">
                                            <a href="{{ route('admin.restaurant-orders.show', $order) }}"
                                               class="text-amber-600 hover:text-amber-700 font-semibold text-xs">
                                                Detail →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
