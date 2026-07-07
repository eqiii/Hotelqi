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
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-[auto_1fr_auto] gap-4 items-center border-b border-gray-100 pb-4">
                                        <div class="w-full md:w-28 h-24 rounded-xl overflow-hidden bg-gray-100">
                                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $menu->name }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ Str::limit($menu->description, 110) }}</p>
                                            <p class="mt-2 text-amber-600 font-semibold">
                                                {{ format_rupiah($menu->price) }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs">Tersedia</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-3xl shadow border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Pesan Sekarang</h2>

                    @auth
                        <form action="{{ route('user.restaurant.order.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-4">
                                @foreach ($menus->flatten() as $menu)
                                    <div class="grid grid-cols-12 gap-3 items-center rounded-xl border border-gray-200 p-3">
                                        <div class="col-span-7">
                                            <label class="font-medium text-gray-800">{{ $menu->name }}</label>
                                            <p class="text-xs text-gray-500">{{ format_rupiah($menu->price) }}</p>
                                        </div>
                                        <div class="col-span-5 text-right">
                                            <input type="hidden" name="menu_id[]" value="{{ $menu->id }}">
                                            <input type="number" name="quantity[]" min="0" value="0"
                                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-amber-600 focus:ring-amber-600" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($bookings->isNotEmpty())
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                                    <p class="text-sm font-semibold text-amber-800 mb-3">Pilih Booking untuk dikaitkan</p>
                                    <select name="booking_id"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-amber-600 focus:ring-amber-600">
                                        <option value="">Tidak ada</option>
                                        @foreach ($bookings as $booking)
                                            <option value="{{ $booking->id }}">{{ $booking->invoice_number }} •
                                                {{ $booking->room->roomType->name }} •
                                                {{ $booking->check_in->format('d M Y') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full bg-amber-600 hover:bg-amber-700 text-white uppercase tracking-widest font-semibold py-3 rounded-xl transition">Buat
                                    Pesanan</button>
                            </div>
                        </form>
                    @else
                        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 text-center">
                            <p class="text-gray-700 font-semibold mb-4">Silakan login terlebih dahulu untuk memesan menu
                                restoran.</p>
                            <a href="{{ route('login') }}"
                                class="inline-flex bg-amber-600 hover:bg-amber-700 text-white uppercase tracking-widest font-semibold py-3 px-6 rounded-xl transition">Login</a>
                        </div>
                    @endauth
                </div>
            </div>

            <div class="text-center text-sm text-gray-500">
                <p>Semua pesanan restoran akan diproses oleh tim layanan kami. Kami akan mengirimkan konfirmasi segera
                    setelah pesanan dikonfirmasi.</p>
            </div>
        </div>
    </section>
</x-guest-layout>
