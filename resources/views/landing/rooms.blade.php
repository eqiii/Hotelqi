<x-guest-layout title="Daftar Kamar">
    <!-- Page Header -->
    <section class="bg-gray-900 text-white py-24 text-center">
        <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Our Rooms</p>
        <h1 class="font-playfair text-5xl font-bold">Luxury Rooms & Suites</h1>
    </section>

    <!-- Rooms Grid -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($roomTypes as $room)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden group hover:shadow-2xl transition-all duration-300">
                        <div class="relative overflow-hidden">
                            <img src="{{ $room->image_url }}" alt="{{ $room->name }}" 
                                 class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute top-4 right-4 bg-amber-600 text-white px-4 py-1 text-sm font-semibold">
                                {{ format_rupiah($room->base_price) }}<span class="text-xs">/night</span>
                            </div>
                        </div>
                        <div class="p-8">
                            <h3 class="font-playfair text-2xl font-bold text-gray-900 mb-3">{{ $room->name }}</h3>
                            <p class="text-gray-600 mb-4 text-sm leading-relaxed">{{ Str::limit($room->description, 100) }}</p>
                            
                            <div class="flex flex-wrap gap-2 mb-6">
                                @foreach($room->facilities->take(3) as $facility)
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 text-xs rounded-full">{{ $facility->name }}</span>
                                @endforeach
                            </div>

                            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                <span class="text-sm text-gray-500">Max {{ $room->max_guest }} Guests • {{ $room->total_bed }} Bed</span>
                                <a href="{{ route('rooms.detail', $room) }}" class="text-amber-600 hover:text-amber-700 text-sm font-semibold tracking-wider uppercase transition">
                                    Details →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-3 py-12">Belum ada kamar tersedia.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $roomTypes->links() }}
            </div>
        </div>
    </section>
</x-guest-layout>