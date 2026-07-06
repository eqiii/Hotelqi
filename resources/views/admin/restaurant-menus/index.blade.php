<x-hotel-app-layout>
    <x-slot name="pageTitle">Restaurant Menus</x-slot>

    <div class="flex items-center justify-between mb-6">
        <h2 class="font-playfair text-2xl font-bold">Restaurant Menus</h2>
        <a href="{{ route('admin.restaurant-menus.create') }}" class="gold-btn">New</a>
    </div>

    <table class="w-full">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $m)
                <tr>
                    <td>{{ $m->name }}</td>
                    <td>{{ format_rupiah($m->price) }}</td>
                    <td>
                        <a href="{{ route('admin.restaurant-menus.edit', $m) }}">Edit</a>
                        <form action="{{ route('admin.restaurant-menus.destroy', $m) }}" method="POST"
                            style="display:inline">@csrf @method('DELETE')<button>Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $menus->links() }}
</x-hotel-app-layout>
