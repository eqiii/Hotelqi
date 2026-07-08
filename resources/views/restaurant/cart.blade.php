<x-guest-layout title="Keranjang Restoran">
    <section class="bg-gray-900 text-white py-20 text-center">
        <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Restaurant</p>
        <h1 class="font-playfair text-4xl font-bold">Keranjang</h1>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            @if (empty($cart))
                <div class="bg-white rounded-3xl shadow border border-gray-100 p-8 text-center text-gray-600">Keranjang
                    Anda masih kosong.</div>
            @else
                <form action="{{ route('user.restaurant.cart.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="bg-white rounded-3xl shadow border border-gray-100 divide-y divide-gray-100">
                        @foreach ($cart as $menuId => $item)
                            @php $menu = $menus->get($menuId); @endphp
                            @if ($menu)
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $menu->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ format_rupiah($item['price']) }} / item</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="number" name="quantities[{{ $menuId }}]"
                                            value="{{ $item['quantity'] }}" min="0" max="10"
                                            class="w-20 rounded-xl border border-gray-300 px-3 py-2 text-center">
                                        <span
                                            class="text-sm font-semibold text-gray-900">{{ format_rupiah(($item['price'] ?? 0) * ($item['quantity'] ?? 0)) }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="flex flex-col md:flex-row gap-3 justify-end">
                        <button type="submit"
                            class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-6 py-3 rounded-xl transition">Update
                            Keranjang</button>
                        <a href="{{ route('user.restaurant.checkout') }}"
                            class="bg-gray-900 hover:bg-gray-700 text-white font-semibold px-6 py-3 rounded-xl transition text-center">Checkout</a>
                    </div>
                </form>
            @endif
        </div>
    </section>
</x-guest-layout>
