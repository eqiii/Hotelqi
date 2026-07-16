<x-guest-layout title="Restaurant Menu">
    <section class="bg-gray-900 text-white py-24 text-center">
        <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Restaurant</p>
        <h1 class="font-playfair text-5xl font-bold">Culinary Delights</h1>
        <p class="mt-4 max-w-2xl mx-auto text-gray-300">Pilih menu favorit Anda untuk dinikmati di dalam kamar atau di
            restoran kami.</p>
    </section>

    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Filter Kategori -->
            <div class="mb-8">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('restaurant') }}" class="px-4 py-2 rounded-full text-sm font-semibold transition {{ !$selectedCategory ? 'bg-amber-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-amber-50' }}">
                        Semua
                    </a>
                    @foreach ($categories as $value => $label)
                        <a href="{{ route('restaurant', ['category' => $value]) }}" class="px-4 py-2 rounded-full text-sm font-semibold transition {{ $selectedCategory == $value ? 'bg-amber-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-amber-50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <div class="lg:col-span-2 space-y-6">
                    @php
                        $categoryOrder = ['food', 'drink', 'dessert', 'snack'];
                    @endphp
                    @foreach ($categoryOrder as $catKey)
                        @if (!isset($menus[$catKey]))
                            @continue
                        @endif
                        @php $items = $menus[$catKey]; @endphp
                        <div class="bg-white rounded-3xl shadow border border-gray-100 overflow-hidden">
                            <div class="bg-amber-600 px-6 py-4 text-white font-semibold tracking-wider uppercase">
                                {{ $categories[$catKey] ?? ucfirst($catKey) }}</div>
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
                        <span id="cart-count-badge" class="text-sm font-semibold text-amber-600">{{ array_sum(array_column(session('restaurant_cart', []), 'quantity')) > 0 ? array_sum(array_column(session('restaurant_cart', []), 'quantity')) . ' item' : '' }}</span>
                    </div>

                    <div id="restaurant-cart-content">
                        @php $cart = session('restaurant_cart', []); @endphp
                        @if (!empty($cart))
                            <div class="space-y-3">
                                @foreach ($cart as $item)
                                    @php $menu = $menus->flatten()->firstWhere('id', $item['menu_id']); @endphp
                                    @if ($menu)
                                        <div class="rounded-xl border border-gray-200 p-3 cart-item" data-menu-id="{{ $menu->id }}">
                                            <div class="flex items-start justify-between mb-2">
                                                <p class="font-semibold text-gray-900 text-sm">{{ $menu->name }}</p>
                                                <span class="text-sm font-semibold text-gray-900 cart-item-subtotal">{{ format_rupiah(($item['price'] ?? 0) * ($item['quantity'] ?? 0)) }}</span>
                                            </div>
                                            <div class="flex items-center justify-between mt-2">
                                                <div class="flex items-center gap-2">
                                                    <button type="button" class="cart-qty-minus w-8 h-8 rounded-full border border-gray-300 text-gray-500 hover:bg-gray-100 flex items-center justify-center font-bold text-lg leading-none transition">−</button>
                                                    <span class="cart-qty-value w-8 text-center font-semibold text-gray-900 text-sm">{{ $item['quantity'] ?? 0 }}</span>
                                                    <button type="button" class="cart-qty-plus w-8 h-8 rounded-full border border-amber-500 text-amber-600 hover:bg-amber-50 flex items-center justify-center font-bold text-lg leading-none transition">+</button>
                                                </div>
                                                <button type="button" class="cart-item-remove text-xs font-medium text-red-500 hover:text-red-700 hover:underline transition flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @php
                                $cartTotal = collect($cart)->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
                            @endphp
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm font-semibold text-gray-700">Total</span>
                                    <span id="cart-grand-total" class="text-lg font-bold text-gray-900">{{ format_rupiah($cartTotal) }}</span>
                                </div>
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
            const cartCountBadge = document.querySelector('#cart-count-badge');
            const addToCartForms = document.querySelectorAll('.restaurant-add-to-cart-form');
            const menuData = @json($menus->flatten()->mapWithKeys(fn($menu) => [$menu->id => ['name' => $menu->name, 'price' => (float) $menu->price]])->all());
            const toast = document.querySelector('#restaurant-toast');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const CART_UPDATE_URL = '{{ route("user.restaurant.cart.update") }}';
            const CART_REMOVE_URL = '{{ route("user.restaurant.cart.remove", ["menuId" => "__MENU_ID__"]) }}';

            function formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                }).format(value);
            }

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&')
                    .replace(/</g, '<')
                    .replace(/>/g, '>')
                    .replace(/"/g, '"')
                    .replace(/'/g, '&#039;');
            }

            function showToast(message, isError = false) {
                if (!toast) {
                    alert(message);
                    return;
                }
                toast.textContent = message;
                toast.className = `fixed right-4 bottom-4 z-50 rounded-2xl px-4 py-3 shadow-xl text-white text-sm font-medium ${isError ? 'bg-rose-600' : 'bg-emerald-600'}`;
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
                    if (cartCountBadge) cartCountBadge.textContent = '';
                    return;
                }

                const itemsHtml = Object.values(cart).map(item => {
                    const menu = menuData[item.menu_id] || {};
                    const name = escapeHtml(menu.name || item.name || 'Menu tidak ditemukan');
                    const price = Number(menu.price ?? item.price ?? 0);
                    const qty = Number(item.quantity ?? 0);
                    const subtotal = formatRupiah(price * qty);
                    return `
                        <div class="rounded-xl border border-gray-200 p-3 cart-item" data-menu-id="${item.menu_id}">
                            <div class="flex items-start justify-between mb-2">
                                <p class="font-semibold text-gray-900 text-sm">${name}</p>
                                <span class="text-sm font-semibold text-gray-900 cart-item-subtotal">${subtotal}</span>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center gap-2">
                                    <button type="button" class="cart-qty-minus w-8 h-8 rounded-full border border-gray-300 text-gray-500 hover:bg-gray-100 flex items-center justify-center font-bold text-lg leading-none transition">−</button>
                                    <span class="cart-qty-value w-8 text-center font-semibold text-gray-900 text-sm">${qty}</span>
                                    <button type="button" class="cart-qty-plus w-8 h-8 rounded-full border border-amber-500 text-amber-600 hover:bg-amber-50 flex items-center justify-center font-bold text-lg leading-none transition">+</button>
                                </div>
                                <button type="button" class="cart-item-remove text-xs font-medium text-red-500 hover:text-red-700 hover:underline transition flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');

                const total = Object.values(cart).reduce((sum, item) => {
                    const price = Number(menuData[item.menu_id]?.price ?? item.price ?? 0);
                    return sum + (price * Number(item.quantity ?? 0));
                }, 0);

                cartContent.innerHTML = `
                    <div class="space-y-3">
                        ${itemsHtml}
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-semibold text-gray-700">Total</span>
                            <span id="cart-grand-total" class="text-lg font-bold text-gray-900">${formatRupiah(total)}</span>
                        </div>
                        <a href="{{ route('user.restaurant.checkout') }}" class="block w-full bg-amber-600 hover:bg-amber-700 text-white text-center uppercase tracking-widest font-semibold py-3 rounded-xl transition">Checkout</a>
                    </div>
                `;

                const totalQty = Object.values(cart).reduce((sum, item) => sum + Number(item.quantity ?? 0), 0);
                if (cartCountBadge) cartCountBadge.textContent = totalQty > 0 ? totalQty + ' item' : '';

                // Re-bind cart events after re-render
                bindCartEvents();
            }

            function bindCartEvents() {
                // Plus buttons
                document.querySelectorAll('.cart-qty-plus').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const itemEl = this.closest('.cart-item');
                        if (!itemEl) return;
                        const menuId = itemEl.dataset.menuId;
                        const currentQtyEl = itemEl.querySelector('.cart-qty-value');
                        const currentQty = parseInt(currentQtyEl.textContent, 10);
                        updateCartQuantity(menuId, currentQty + 1);
                    });
                });

                // Minus buttons
                document.querySelectorAll('.cart-qty-minus').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const itemEl = this.closest('.cart-item');
                        if (!itemEl) return;
                        const menuId = itemEl.dataset.menuId;
                        const currentQtyEl = itemEl.querySelector('.cart-qty-value');
                        const currentQty = parseInt(currentQtyEl.textContent, 10);
                        if (currentQty <= 1) {
                            removeCartItem(menuId);
                        } else {
                            updateCartQuantity(menuId, currentQty - 1);
                        }
                    });
                });

                // Remove buttons
                document.querySelectorAll('.cart-item-remove').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const itemEl = this.closest('.cart-item');
                        if (!itemEl) return;
                        const menuId = itemEl.dataset.menuId;
                        removeCartItem(menuId);
                    });
                });
            }

            function updateCartQuantity(menuId, newQuantity) {
                const body = new URLSearchParams();
                body.append('menu_id', menuId);
                body.append('quantity', newQuantity);
                body.append('_token', csrfToken);

                fetch(CART_UPDATE_URL, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    },
                    body: body.toString(),
                    credentials: 'same-origin',
                })
                    .then(async response => {
                        const data = await response.json().catch(() => null);
                        if (!response.ok) {
                            if (response.status === 401) {
                                const loginUrl = data?.redirect || '{{ route('login') }}';
                                window.location.href = loginUrl;
                                return;
                            }
                            showToast(data?.message || 'Gagal memperbarui keranjang.', true);
                            return;
                        }
                        if (data && data.success) {
                            renderCart(data.cart || {});
                            showToast('Keranjang diperbarui!');
                        }
                    })
                    .catch(() => {
                        showToast('Koneksi gagal. Silakan coba lagi.', true);
                    });
            }

            function removeCartItem(menuId) {
                const url = CART_REMOVE_URL.replace('__MENU_ID__', menuId);
                const body = new URLSearchParams();
                body.append('_token', csrfToken);
                body.append('_method', 'DELETE');

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    },
                    body: body.toString(),
                    credentials: 'same-origin',
                })
                    .then(async response => {
                        const data = await response.json().catch(() => null);
                        if (!response.ok) {
                            if (response.status === 401) {
                                const loginUrl = data?.redirect || '{{ route('login') }}';
                                window.location.href = loginUrl;
                                return;
                            }
                            showToast(data?.message || 'Gagal menghapus item.', true);
                            return;
                        }
                        if (data && data.success) {
                            renderCart(data.cart || {});
                            showToast('Item dihapus dari keranjang.');
                        }
                    })
                    .catch(() => {
                        showToast('Koneksi gagal. Silakan coba lagi.', true);
                    });
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
                    redirect: 'follow',
                })
                    .then(async response => {
                        const data = await response.json().catch(() => null);

                        if (!response.ok) {
                            // 401 Unauthenticated — redirect to login
                            if (response.status === 401) {
                                const loginUrl = data?.redirect || '{{ route('login') }}';
                                window.location.href = loginUrl;
                                return;
                            }
                            // 422 Validation error or other server error
                            const msg = data?.message || data?.errors?.menu_id?.[0] || 'Gagal menambahkan menu.';
                            showToast(msg, true);
                            return;
                        }

                        if (!data || data.success !== true) {
                            showToast(data?.message || 'Gagal menambahkan menu.', true);
                            return;
                        }

                        renderCart(data.cart || {});
                        showToast('Menu berhasil ditambahkan ke keranjang!');
                    })
                    .catch(() => {
                        showToast('Koneksi gagal. Silakan coba lagi.', true);
                    });
            }

            addToCartForms.forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    submitAddToCart(form);
                });
            });

            // Initial bind for cart events if items exist
            bindCartEvents();
        })();
    </script>
</x-guest-layout>
