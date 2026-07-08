<x-guest-layout title="Checkout Restoran">
    <section class="bg-gray-900 text-white py-20 text-center">
        <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Restaurant</p>
        <h1 class="font-playfair text-4xl font-bold">Checkout</h1>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <form action="{{ route('user.restaurant.checkout.store') }}" method="POST"
                class="grid grid-cols-1 lg:grid-cols-[1.2fr_0.8fr] gap-8">
                @csrf
                <div class="space-y-6">
                    <div class="bg-white rounded-3xl shadow border border-gray-100 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Waktu Penyajian</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label
                                class="rounded-xl border border-gray-200 p-4 cursor-pointer {{ old('serve_type', 'now') === 'now' ? 'border-amber-500 bg-amber-50' : '' }}">
                                <input type="radio" name="serve_type" value="now" class="mr-2"
                                    {{ old('serve_type', 'now') === 'now' ? 'checked' : '' }}> Serve Now
                            </label>
                            <label
                                class="rounded-xl border border-gray-200 p-4 cursor-pointer {{ old('serve_type') === 'scheduled' ? 'border-amber-500 bg-amber-50' : '' }}">
                                <input type="radio" name="serve_type" value="scheduled" class="mr-2"
                                    {{ old('serve_type') === 'scheduled' ? 'checked' : '' }}> Schedule Time
                            </label>
                        </div>
                        <input type="datetime-local" name="serve_time" value="{{ old('serve_time') }}"
                            class="mt-4 w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-600 focus:ring-amber-600">
                    </div>

                    <div class="bg-white rounded-3xl shadow border border-gray-100 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Opsi Pengantaran</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label
                                class="rounded-xl border border-gray-200 p-4 cursor-pointer {{ old('dining_type', 'dine_in') === 'dine_in' ? 'border-amber-500 bg-amber-50' : '' }}">
                                <input type="radio" name="dining_type" value="dine_in" class="mr-2"
                                    {{ old('dining_type', 'dine_in') === 'dine_in' ? 'checked' : '' }}> Eat at
                                Restaurant
                            </label>
                            <label
                                class="rounded-xl border border-gray-200 p-4 cursor-pointer {{ old('dining_type') === 'room_service' ? 'border-amber-500 bg-amber-50' : '' }}">
                                <input type="radio" name="dining_type" value="room_service" class="mr-2"
                                    {{ old('dining_type') === 'room_service' ? 'checked' : '' }}> Room Service
                            </label>
                        </div>

                        <div class="mt-4 space-y-4">
                            <div id="guest-name-block">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Guest Name</label>
                                <input type="text" name="guest_name" value="{{ old('guest_name') }}"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-600 focus:ring-amber-600">
                            </div>
                            <div id="room-number-block" class="hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Room Number</label>
                                @if ($activeBooking)
                                    <input type="text" name="room_number"
                                        value="{{ old('room_number', $activeBooking->room->room_number) }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-600 focus:ring-amber-600">
                                @else
                                    <input type="text" name="room_number" value="{{ old('room_number') }}"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-600 focus:ring-amber-600">
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl shadow border border-gray-100 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">3. Ringkasan Order</h2>
                        <div class="space-y-3">
                            @foreach ($cart as $item)
                                @php $menu = $menus->get($item['menu_id']); @endphp
                                @if ($menu)
                                    <div class="flex items-center justify-between text-sm text-gray-600">
                                        <span>{{ $menu->name }} x {{ $item['quantity'] }}</span>
                                        <span>{{ format_rupiah(($item['price'] ?? 0) * ($item['quantity'] ?? 0)) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow border border-gray-100 p-6 h-fit">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Payment</h2>
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-3">
                            <input type="radio" name="payment_method" value="midtrans"
                                {{ old('payment_method', 'midtrans') === 'midtrans' ? 'checked' : '' }}>
                            <span>Midtrans</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-3">
                            <input type="radio" name="payment_method" value="manual_transfer"
                                {{ old('payment_method') === 'manual_transfer' ? 'checked' : '' }}>
                            <span>Manual Transfer</span>
                        </label>
                        <textarea name="notes" rows="4" placeholder="Catatan tambahan"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-600 focus:ring-amber-600">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit"
                        class="mt-6 w-full bg-amber-600 hover:bg-amber-700 text-white uppercase tracking-widest font-semibold py-3 rounded-xl transition">Lanjut
                        ke Pembayaran</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        const diningTypeInputs = document.querySelectorAll('input[name="dining_type"]');
        const guestNameBlock = document.getElementById('guest-name-block');
        const roomNumberBlock = document.getElementById('room-number-block');

        function syncDiningFields() {
            const selected = document.querySelector('input[name="dining_type"]:checked')?.value;
            if (selected === 'room_service') {
                guestNameBlock.classList.add('hidden');
                roomNumberBlock.classList.remove('hidden');
            } else {
                guestNameBlock.classList.remove('hidden');
                roomNumberBlock.classList.add('hidden');
            }
        }

        diningTypeInputs.forEach((input) => input.addEventListener('change', syncDiningFields));
        syncDiningFields();
    </script>
</x-guest-layout>
