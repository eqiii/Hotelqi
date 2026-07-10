<x-guest-layout title="Checkout Restoran">
    <section class="bg-gray-900 text-white py-20 text-center">
        <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Restaurant</p>
        <h1 class="font-playfair text-4xl font-bold">Checkout</h1>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">

            {{-- Global validation error alert --}}
            @if ($errors->any())
                <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700 space-y-1">
                    <p class="font-semibold">Terdapat kesalahan pada formulir:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.restaurant.checkout.store') }}" method="POST"
                class="grid grid-cols-1 lg:grid-cols-[1.2fr_0.8fr] gap-8">
                @csrf
                <div class="space-y-6">

                    {{-- ── 1. Waktu Penyajian ─────────────────────────────── --}}
                    <div class="bg-white rounded-3xl shadow border border-gray-100 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Waktu Penyajian</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label id="serve-now-label"
                                class="rounded-xl border border-gray-200 p-4 cursor-pointer {{ old('serve_type', 'now') === 'now' ? 'border-amber-500 bg-amber-50' : '' }}">
                                <input type="radio" name="serve_type" value="now" id="serve_now"
                                    class="mr-2" {{ old('serve_type', 'now') === 'now' ? 'checked' : '' }}>
                                Serve Now
                            </label>
                            <label id="serve-scheduled-label"
                                class="rounded-xl border border-gray-200 p-4 cursor-pointer {{ old('serve_type') === 'scheduled' ? 'border-amber-500 bg-amber-50' : '' }}">
                                <input type="radio" name="serve_type" value="scheduled" id="serve_scheduled"
                                    class="mr-2" {{ old('serve_type') === 'scheduled' ? 'checked' : '' }}>
                                Schedule Time
                            </label>
                        </div>
                        <div id="serve-time-block" class="{{ old('serve_type') === 'scheduled' ? '' : 'hidden' }} mt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Waktu Penyajian</label>
                            <input type="datetime-local" name="serve_time" id="serve_time"
                                value="{{ old('serve_time') }}"
                                class="w-full rounded-xl border {{ $errors->has('serve_time') ? 'border-red-400' : 'border-gray-300' }} px-4 py-3 focus:border-amber-600 focus:ring-amber-600">
                            @error('serve_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ── 2. Opsi Pengantaran ────────────────────────────── --}}
                    <div class="bg-white rounded-3xl shadow border border-gray-100 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Opsi Pengantaran</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label id="dine-in-label"
                                class="rounded-xl border border-gray-200 p-4 cursor-pointer {{ old('dining_type', 'dine_in') === 'dine_in' ? 'border-amber-500 bg-amber-50' : '' }}">
                                <input type="radio" name="dining_type" value="dine_in"
                                    {{ old('dining_type', 'dine_in') === 'dine_in' ? 'checked' : '' }}>
                                &nbsp;🍽️ Eat at Restaurant
                            </label>
                            <label id="room-service-label"
                                class="rounded-xl border border-gray-200 p-4 cursor-pointer {{ old('dining_type') === 'room_service' ? 'border-amber-500 bg-amber-50' : '' }}">
                                <input type="radio" name="dining_type" value="room_service"
                                    {{ old('dining_type') === 'room_service' ? 'checked' : '' }}>
                                &nbsp;🛎️ Deliver to Room
                            </label>
                        </div>

                        @error('dining_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-4 space-y-4">
                            {{-- Guest Name (shown for dine_in) --}}
                            <div id="guest-name-block" class="{{ old('dining_type', 'dine_in') === 'room_service' ? 'hidden' : '' }}">
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="guest_name">
                                    Nama Tamu <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="guest_name" id="guest_name"
                                    value="{{ old('guest_name', Auth::user()->name) }}"
                                    placeholder="Masukkan nama tamu"
                                    class="w-full rounded-xl border {{ $errors->has('guest_name') ? 'border-red-400' : 'border-gray-300' }} px-4 py-3 focus:border-amber-600 focus:ring-amber-600">
                                @error('guest_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Room Number (shown for room_service) --}}
                            <div id="room-number-block" class="{{ old('dining_type', 'dine_in') === 'room_service' ? '' : 'hidden' }}">
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="room_number">
                                    Nomor Kamar <span class="text-red-500">*</span>
                                </label>
                                @if ($activeBooking)
                                    <input type="text" name="room_number" id="room_number"
                                        value="{{ old('room_number', $activeBooking->room->room_number) }}"
                                        class="w-full rounded-xl border {{ $errors->has('room_number') ? 'border-red-400' : 'border-gray-300' }} px-4 py-3 focus:border-amber-600 focus:ring-amber-600">
                                    <p class="mt-1 text-xs text-gray-500">
                                        Kamar aktif Anda: <strong>{{ $activeBooking->room->room_number }}</strong>
                                    </p>
                                @else
                                    <input type="text" name="room_number" id="room_number"
                                        value="{{ old('room_number') }}"
                                        placeholder="Contoh: 101"
                                        class="w-full rounded-xl border {{ $errors->has('room_number') ? 'border-red-400' : 'border-gray-300' }} px-4 py-3 focus:border-amber-600 focus:ring-amber-600">
                                    <p class="mt-1 text-xs text-amber-600 font-medium">
                                        Anda harus memiliki booking aktif (checked-in) untuk memesan layanan kamar.
                                    </p>
                                @endif
                                @error('room_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── 3. Ringkasan Order ──────────────────────────────── --}}
                    <div class="bg-white rounded-3xl shadow border border-gray-100 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">3. Ringkasan Order</h2>
                        <div class="space-y-3">
                            @php $orderSubtotal = 0; @endphp
                            @foreach ($cart as $item)
                                @php $menu = $menus->get($item['menu_id']); @endphp
                                @if ($menu)
                                    @php $lineTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 0); $orderSubtotal += $lineTotal; @endphp
                                    <div class="flex items-center justify-between text-sm text-gray-600">
                                        <span>{{ $menu->name }} <span class="text-gray-400">x{{ $item['quantity'] }}</span></span>
                                        <span>{{ format_rupiah($lineTotal) }}</span>
                                    </div>
                                @endif
                            @endforeach
                            <div class="pt-3 border-t border-gray-100 flex justify-between text-sm font-semibold text-gray-800">
                                <span>Subtotal</span>
                                <span>{{ format_rupiah($orderSubtotal) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Tax (10%)</span>
                                <span>{{ format_rupiah(round($orderSubtotal * 0.1, 0)) }}</span>
                            </div>
                            <div class="flex justify-between text-base font-bold text-amber-600">
                                <span>Total</span>
                                <span>{{ format_rupiah(round($orderSubtotal * 1.1, 0)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── 4. Payment ────────────────────────────────────────── --}}
                <div class="bg-white rounded-3xl shadow border border-gray-100 p-6 h-fit">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Pembayaran</h2>
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-3 cursor-pointer {{ old('payment_method', 'midtrans') === 'midtrans' ? 'border-amber-500 bg-amber-50' : '' }}">
                            <input type="radio" name="payment_method" value="midtrans"
                                {{ old('payment_method', 'midtrans') === 'midtrans' ? 'checked' : '' }}>
                            <span class="font-medium">💳 Midtrans (Online)</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-xl border border-gray-200 p-3 cursor-pointer {{ old('payment_method') === 'manual_transfer' ? 'border-amber-500 bg-amber-50' : '' }}">
                            <input type="radio" name="payment_method" value="manual_transfer"
                                {{ old('payment_method') === 'manual_transfer' ? 'checked' : '' }}>
                            <span class="font-medium">🏦 Manual Transfer</span>
                        </label>
                        @error('payment_method')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2" for="notes">Catatan (opsional)</label>
                            <textarea name="notes" id="notes" rows="3"
                                placeholder="Alergi, permintaan khusus, dll."
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-amber-600 focus:ring-amber-600">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <button type="submit"
                        class="mt-6 w-full bg-amber-600 hover:bg-amber-700 text-white uppercase tracking-widest font-semibold py-3 rounded-xl transition">
                        Lanjut ke Pembayaran →
                    </button>
                    <a href="{{ route('user.restaurant.cart') }}"
                        class="mt-3 block text-center text-sm text-gray-500 hover:text-gray-700">← Kembali ke Keranjang</a>
                </div>
            </form>
        </div>
    </section>

    <script>
        (function () {
            const diningTypeInputs  = document.querySelectorAll('input[name="dining_type"]');
            const serveTypeInputs   = document.querySelectorAll('input[name="serve_type"]');
            const guestNameBlock    = document.getElementById('guest-name-block');
            const roomNumberBlock   = document.getElementById('room-number-block');
            const serveTimeBlock    = document.getElementById('serve-time-block');
            const serveTimeInput    = document.getElementById('serve_time');
            const dineInLabel       = document.getElementById('dine-in-label');
            const roomServiceLabel  = document.getElementById('room-service-label');
            const serveNowLabel     = document.getElementById('serve-now-label');
            const serveScheduledLabel = document.getElementById('serve-scheduled-label');

            function syncDiningFields() {
                const selected = document.querySelector('input[name="dining_type"]:checked')?.value;
                if (selected === 'room_service') {
                    guestNameBlock.classList.add('hidden');
                    roomNumberBlock.classList.remove('hidden');
                    dineInLabel.classList.remove('border-amber-500', 'bg-amber-50');
                    roomServiceLabel.classList.add('border-amber-500', 'bg-amber-50');
                } else {
                    guestNameBlock.classList.remove('hidden');
                    roomNumberBlock.classList.add('hidden');
                    roomServiceLabel.classList.remove('border-amber-500', 'bg-amber-50');
                    dineInLabel.classList.add('border-amber-500', 'bg-amber-50');
                }
            }

            function syncServeTimeFields() {
                const selected = document.querySelector('input[name="serve_type"]:checked')?.value;
                if (selected === 'scheduled') {
                    serveTimeBlock.classList.remove('hidden');
                    if (serveTimeInput) serveTimeInput.required = true;
                    serveNowLabel.classList.remove('border-amber-500', 'bg-amber-50');
                    serveScheduledLabel.classList.add('border-amber-500', 'bg-amber-50');
                } else {
                    serveTimeBlock.classList.add('hidden');
                    if (serveTimeInput) serveTimeInput.required = false;
                    serveScheduledLabel.classList.remove('border-amber-500', 'bg-amber-50');
                    serveNowLabel.classList.add('border-amber-500', 'bg-amber-50');
                }
            }

            diningTypeInputs.forEach(i => i.addEventListener('change', syncDiningFields));
            serveTypeInputs.forEach(i => i.addEventListener('change', syncServeTimeFields));

            // Run on page load to reflect old() values after validation failure
            syncDiningFields();
            syncServeTimeFields();
        })();
    </script>
</x-guest-layout>
