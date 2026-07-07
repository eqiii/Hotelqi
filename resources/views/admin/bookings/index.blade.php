@extends('layouts.admin')

@section('pageTitle')
    Bookings
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Bookings</h2>

    <table class="w-full mt-4">
        <thead><tr><th>Invoice</th><th>Guest</th><th>Room</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($bookings as $b)
                <tr>
                    <td>{{ $b->invoice_number }}</td>
                    <td>{{ $b->guest->user->name ?? '-' }}</td>
                    <td>{{ $b->room->roomType->name ?? '-' }}</td>
                    <td>{{ $b->status }}</td>
                    <td>
                        <a href="{{ route('admin.bookings.show', $b) }}">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $bookings->links() }}
@endsection
