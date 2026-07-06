<x-hotel-app-layout>
    <x-slot name="pageTitle">Create Menu</x-slot>

    <h2 class="font-playfair text-2xl font-bold">Create Menu Item</h2>

    <form method="POST" action="{{ route('admin.restaurant-menus.store') }}" class="mt-4">
        @csrf
        <div>
            <label>Name</label>
            <input name="name" required />
        </div>
        <div>
            <label>Price</label>
            <input name="price" required type="number" step="0.01" />
        </div>
        <div>
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>
        <button class="gold-btn mt-3">Create</button>
    </form>
</x-hotel-app-layout>
