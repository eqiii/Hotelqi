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
                    <td class="flex items-center gap-3">
                        <img src="{{ $m->image_url }}" alt="{{ $m->name }}" class="h-12 w-20 rounded-lg object-cover" />
                        {{ $m->name }}
                    </td>
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
