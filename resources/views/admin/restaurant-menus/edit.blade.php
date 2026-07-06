<x-hotel-app-layout>
    <x-slot name="pageTitle">Edit Menu</x-slot>

    <h2 class="font-playfair text-2xl font-bold">Edit Menu Item</h2>

    <form method="POST" action="{{ route('admin.restaurant-menus.update', $restaurantMenu) }}" class="mt-4" enctype="multipart/form-data">@csrf
        @method('PATCH')
        <div>
            <label>Name</label>
            <input name="name" value="{{ old('name', $restaurantMenu->name) }}" required />
        </div>
        <div>
            <label>Price</label>
            <input name="price" value="{{ old('price', $restaurantMenu->price) }}" required type="number"
                step="0.01" />
        </div>
        <div>
            <label>Description</label>
            <textarea name="description">{{ old('description', $restaurantMenu->description) }}</textarea>
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png" />
            @if ($restaurantMenu->image)
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $restaurantMenu->image) }}" alt="{{ $restaurantMenu->name }}" class="h-32 rounded-lg object-cover" />
                </div>
            @endif
        </div>
        <button class="gold-btn mt-3">Save</button>
    </form>
</x-hotel-app-layout>
