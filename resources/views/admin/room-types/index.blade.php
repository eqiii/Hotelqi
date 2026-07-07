@extends('layouts.admin')

@section('pageTitle')
    Room Types
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-playfair text-2xl font-bold">Room Types</h2>
        <a href="{{ route('admin.room-types.create') }}" class="gold-btn">New</a>
    </div>

    <table class="w-full">
        <thead>
            <tr><th>Name</th><th>Price</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($types as $type)
                <tr>
                    <td>{{ $type->name }}</td>
                    <td>{{ format_rupiah($type->base_price ?? 0) }}</td>
                    <td>
                        <a href="{{ route('admin.room-types.edit', $type) }}">Edit</a>
                        <form action="{{ route('admin.room-types.destroy', $type) }}" method="POST" style="display:inline">@csrf @method('DELETE')<button>Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $types->links() }}
@endsection
