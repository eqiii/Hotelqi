@extends('layouts.admin')

@section('pageTitle')
    Booking {{ $booking->invoice_number }}
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Booking {{ $booking->invoice_number }}</h2>

    <div class="mt-4">
        <p>Guest: {{ $booking->guest->user->name ?? '-' }}</p>
        <p>Room: {{ $booking->room->roomType->name ?? '-' }} - {{ $booking->room->room_number }}</p>
        <p>Check-in: {{ $booking->check_in->format('d M Y') }}</p>
        <p>Check-out: {{ $booking->check_out->format('d M Y') }}</p>
        <p>Status: {{ $booking->status }}</p>
    </div>

    <div class="mt-4 space-x-2">
        <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}">@csrf<button
                class="gold-btn">Confirm</button></form>
                        <form method="POST" action="{{ route('admin.bookings.checkin', $booking) }}">@csrf<button class="gold-btn">Check
                In</button></form>
        <form method="POST" action="{{ route('admin.bookings.checkout', $booking) }}">@csrf<button
                class="gold-btn">Check Out</button></form>
        <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}">@csrf<button
                class="gold-btn">Cancel</button></form>
    </div>
@endsection
