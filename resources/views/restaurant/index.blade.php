   <x-guest-layout title="Restaurant Menu">
    <section class="bg-gray-900 text-white py-24 text-center">
        <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Restaurant</p>
        <h1 class="font-playfair text-5xl font-bold">Culinary Delights</h1>
        <p class="mt-4 max-w-2xl mx-auto text-gray-300">Pilih menu favorit Anda untuk dinikmati di dalam kamar atau di
            restoran kami.</p>
    </section>

    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <div class="lg:col-span-2 space-y-6">
                    @foreach ($menus as $category => $items)
                        <div class="bg-white rounded-3xl shadow border border-gray-100 overflow-hidden">
                            <div class="bg-amber-600 px-6 py-4 text-white font-semibold tracking-wider uppercase">
                                {{ $category }}</div>
                            <div class="p-6 space-y-6">
                                @foreach ($items as $menu)
                                    <div class="grid grid-cols-1 md:grid-cols-[auto_1fr_auto] gap-4 items-center border-b border-gray-100 pb-4">
                                        <div class="w-full md:w-28 h-24 rounded-xl overflow-hidden bg-gray-100">
                                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $menu->name }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($menu->description, 110) }}</p>
                                            <p class="mt-2 text-amber-600 font-semibold">{{ format_rupiah($menu->price) }}</p>
                                            <p class="mt-1 text-xs text-gray-500">{{ $menu->stock_quantity ? 'Stok: ' . $menu->stock_quantity : 'Stok tidak dibatasi' }}</p>
                                        </div>
                                        <div class="text-right space-y-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full {{ $menu->is_available ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }} text-xs">
                                                {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                            </span>
                                            @auth
                                                <form action="{{ route('user.restaurant.cart.add') }}" method="POST" class="restaurant-add-to-cart-form flex items-center gap-2 justify-end">
                                                    @csrf
                                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                                    <input type="number" name="quantity" min="1" max="10" value="1" class="w-16 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-amber-600 focus:ring-amber-600">
                                                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">Tambah</button>
                                                </form>
                                            @endauth
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-3xl shadow border border-gray-100 p-6 h-fit sticky top-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Keranjang</h2>
                        <a href="{{ route('user.restaurant.cart') }}" class="text-sm font-semibold text-amber-600">Lihat</a>
                    </div>

                    <div id="restaurant-cart-content">
                        @php $cart = session('restaurant_cart', []); @endphp
                        @if (!empty($cart))
                            <div class="space-y-3">
                                @foreach ($cart as $item)
                                    @php $menu = $menus->flatten()->firstWhere('id', $item['menu_id']); @endphp
                                    @if ($menu)
                                        <div class="rounded-xl border border-gray-200 p-3">
                                            <div class="flex items-center justify-between">
                                                <p class="font-semibold text-gray-900">{{ $menu->name }}</p>
                                                <span class="text-sm text-gray-600">{{ format_rupiah(($item['price'] ?? 0) * ($item['quantity'] ?? 0)) }}</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Qty: {{ $item['quantity'] ?? 0 }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="mt-6 border-t border-gray-200 pt-4">
                                <a href="{{ route('user.restaurant.checkout') }}" class="block w-full bg-amber-600 hover:bg-amber-700 text-white text-center uppercase tracking-widest font-semibold py-3 rounded-xl transition">Checkout</a>
                            </div>
                        @else
                            <div class="rounded-3xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">
                                Keranjang Anda masih kosong.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-center text-sm text-gray-500">
                <p>Semua pesanan restoran akan diproses oleh tim layanan kami. Kami akan mengirimkan konfirmasi segera setelah pesanan dikonfirmasi.</p>
            </div>
        </div>
    </section>

    <div id="restaurant-toast" class="fixed right-4 bottom-4 z-50 hidden rounded-2xl px-4 py-3 shadow-xl bg-emerald-600 text-white text-sm font-medium"></div>

    <script>
        (function () {
            const cartContent = document.querySelector('#restaurant-cart-content');
            const addToCartForms = document.querySelectorAll('.restaurant-add-to-cart-form');
            const menuData = @json($menus->flatten()->mapWithKeys(fn($menu) => [$menu->id => ['name' => $menu->name, 'price' => (float) $menu->price]])->all());
            const toast = document.querySelector('#restaurant-toast');

            function formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                }).format(value);
            }

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function showToast(message) {
                if (!toast) {
                    alert(message);
                    return;
                }
                toast.textContent = message;
                toast.classList.remove('hidden', 'opacity-0');
                toast.classList.add('opacity-100');
                clearTimeout(window.restaurantCartToastTimeout);
                window.restaurantCartToastTimeout = setTimeout(() => {
                    toast.classList.add('opacity-0');
                    toast.addEventListener('transitionend', () => toast.classList.add('hidden'), { once: true });
                }, 2400);
            }

            function renderCart(cart) {
                if (!cart || Object.keys(cart).length === 0) {
                    cartContent.innerHTML = `
                        <div class="rounded-3xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">
                            Keranjang Anda masih kosong.
                        </div>
                    `;
                    return;
                }

                const itemsHtml = Object.values(cart).map(item => {
                    const menu = menuData[item.menu_id] || {};
                    const name = escapeHtml(menu.name || item.name || 'Menu tidak ditemukan');
                    const price = Number(menu.price ?? item.price ?? 0);
                    const total = formatRupiah(price * Number(item.quantity ?? 0));
                    return `
                        <div class="rounded-xl border border-gray-200 p-3">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-gray-900">${name}</p>
                                <span class="text-sm text-gray-600">${total}</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Qty: ${Number(item.quantity ?? 0)}</p>
                        </div>
                    `;
                }).join('');

                cartContent.innerHTML = `
                    <div class="space-y-3">
                        ${itemsHtml}
                    </div>
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <a href="{{ route('user.restaurant.checkout') }}" class="block w-full bg-amber-600 hover:bg-amber-700 text-white text-center uppercase tracking-widest font-semibold py-3 rounded-xl transition">Checkout</a>
                    </div>
                `;
            }

            function submitAddToCart(form) {
                const formData = new FormData(form);
                const body = new URLSearchParams();
                for (const [key, value] of formData.entries()) {
                    body.append(key, value);
                }

                fetch(form.action, {
                    method: form.method || 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    },
                    body: body.toString(),
                    credentials: 'same-origin',
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data || data.success !== true) {
                            throw new Error('Response indicates failure');
                        }

                        renderCart(data.cart || {});
                        showToast('Menu berhasil ditambahkan.');
                    })
                    .catch(() => {
                        alert('Gagal menambahkan menu. Silakan coba lagi.');
                    });
            }

            addToCartForms.forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    submitAddToCart(form);
                });
            });
        })();
    </script>
</x-guest-layout>
