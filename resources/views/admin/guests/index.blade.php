@extends('layouts.admin')

@section('pageTitle')
    Guests
@endsection

@section('content')
    <h2 class="font-playfair text-2xl font-bold">Guests</h2>

    <table class="w-full mt-4">
        <thead><tr><th>Name</th><th>Email</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($guests as $g)
                <tr>
                    <td>{{ $g->user->name ?? '-' }}</td>
                    <td>{{ $g->user->email ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.guests.show', $g) }}">View</a>
                        <a href="{{ route('admin.guests.edit', $g) }}">Edit</a>
                        <form action="{{ route('admin.guests.destroy', $g) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button>Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $guests->links() }}
@endsection
