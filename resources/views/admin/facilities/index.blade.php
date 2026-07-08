@extends('layouts.admin')

@section('pageTitle')
    Facilities
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-playfair text-2xl font-bold">Facilities</h2>
        <a href="{{ route('admin.facilities.create') }}" class="gold-btn">New</a>
    </div>

    <table class="w-full">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Icon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facilities as $f)
                <tr>
                    <td>
                        <img src="{{ $f->image_url }}" alt="{{ $f->name }}" class="h-12 w-20 rounded-lg object-cover" />
                    </td>
                    <td>{{ $f->name }}</td>
                    <td>{{ $f->icon ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.facilities.edit', $f) }}">Edit</a>
                        <form action="{{ route('admin.facilities.destroy', $f) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')<button>Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $facilities->links() }}
@endsection
