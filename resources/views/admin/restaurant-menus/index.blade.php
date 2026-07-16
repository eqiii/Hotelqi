@extends('layouts.admin')

@section('pageTitle')
    Restaurant Menus
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-playfair text-2xl font-bold">Restaurant Menus</h2>
        <a href="{{ route('admin.restaurant-menus.create') }}" class="gold-btn">New</a>
    </div>

    <div class="mb-4">
        <form method="GET" action="{{ route('admin.restaurant-menus.index') }}" class="flex items-center gap-2">
            <select name="category" class="rounded-lg border border-gray-300 px-3 py-2">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" {{ request('category') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">Filter</button>
            @if (request('category'))
                <a href="{{ route('admin.restaurant-menus.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Reset</a>
            @endif
        </form>
    </div>

    <table class="w-full">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $m)
                <tr>
                    <td class="flex items-center gap-3">
                        <img src="{{ $m->image_url }}" alt="{{ $m->name }}" class="h-12 w-20 rounded-lg object-cover" />
                        {{ $m->name }}
                    </td>
                    <td><span class="px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">{{ $m->category_label }}</span></td>
                    <td>{{ format_rupiah($m->price) }}</td>
                    <td>
                        <a href="{{ route('admin.restaurant-menus.edit', $m) }}" class="text-amber-600 hover:text-amber-800 font-semibold text-sm">Edit</a>
                        <form action="{{ route('admin.restaurant-menus.destroy', $m) }}" method="POST"
                            style="display:inline">@csrf @method('DELETE')<button class="text-red-600 hover:text-red-800 font-semibold text-sm ml-2">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $menus->links() }}
@endsection
