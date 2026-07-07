@extends('layouts.admin')

@section('pageTitle')
    Rooms
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-playfair text-2xl font-bold">Rooms</h2>
        <a href="{{ route('admin.rooms.create') }}" class="gold-btn">New Room</a>
    </div>

    <table class="w-full">
        <thead><tr><th>No</th><th>Type</th><th>Number</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($rooms as $room)
                <tr>
                    <td>{{ $room->id }}</td>
                    <td>{{ $room->roomType->name ?? '-' }}</td>
                    <td>{{ $room->room_number }}</td>
                    <td>{{ $room->status }}</td>
                    <td>
                        <a href="{{ route('admin.rooms.edit', $room) }}">Edit</a>
                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button>Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $rooms->links() }}
@endsection
