@extends('layouts.admin')

@section('pageTitle')
    Booking {{ $booking->invoice_number }}
@endsection

@section('content')
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Main Booking Details --}}
        <div class="flex-1">
            <h2 class="font-playfair text-2xl font-bold">Booking {{ $booking->invoice_number }}</h2>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Guest</p>
                    <p class="font-semibold">{{ $booking->guest->user->name ?? '-' }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Room</p>
                    <p class="font-semibold">{{ $booking->room->roomType->name ?? '-' }} - {{ $booking->room->room_number }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Check-in</p>
                    <p class="font-semibold">{{ $booking->check_in->format('d M Y') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Check-out</p>
                    <p class="font-semibold">{{ $booking->check_out->format('d M Y') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $booking->status_badge }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Total Harga</p>
                    <p class="font-bold text-lg">{{ format_rupiah($booking->total_price) }}</p>
                </div>
                @if($booking->payment)
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Payment Status</p>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $booking->payment->status_badge }}">
                        {{ strtoupper($booking->payment->payment_status) }}
                    </span>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Payment Method</p>
                    <p class="font-semibold">{{ $booking->payment->payment_method ?? '-' }}</p>
                </div>
                @endif
            </div>

            @if($booking->payment && $booking->payment->isPaid())
            {{-- QR Code Section --}}
            <div class="mt-6 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="font-playfair text-lg font-bold mb-4">Booking QR Code</h3>
                <p class="text-sm text-gray-500 mb-3">Scan QR ini untuk verifikasi booking oleh resepsionis.</p>
                <div class="inline-block bg-gray-50 p-4 rounded-xl border border-gray-200">
                    {!! QrCode::size(150)->generate($booking->id) !!}
                </div>
                <p class="text-xs text-gray-400 mt-2">Booking ID: #{{ $booking->id }}</p>
            </div>
            @endif
        </div>

        {{-- Actions Sidebar --}}
        <div class="lg:w-72">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="font-playfair text-lg font-bold mb-4">Actions</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}">
                        @csrf
                        <button class="w-full gold-btn py-2.5 rounded-lg text-sm font-semibold">Confirm</button>
                    </form>
                    <form method="POST" action="{{ route('admin.bookings.checkin', $booking) }}">
                        @csrf
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-semibold transition">Check In</button>
                    </form>
                    <form method="POST" action="{{ route('admin.bookings.checkout', $booking) }}">
                        @csrf
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg text-sm font-semibold transition">Check Out</button>
                    </form>
                    <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}">
                        @csrf
                        <button class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-lg text-sm font-semibold transition">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
