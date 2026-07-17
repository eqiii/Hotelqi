<x-guest-layout title="Beranda">
    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex flex-col justify-center relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center pt-32 pb-48">
            <!-- Subtitle -->
            <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Luxury Hotel & Resort</p>
            
            <!-- Stars -->
            <div class="flex justify-center space-x-1 mb-6">
                @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-amber-400 fill-current" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                    </svg>
                @endfor
            </div>

            <!-- Main Heading -->
            <h1 class="font-playfair text-5xl md:text-7xl font-bold text-white mb-8 leading-tight">
                THE BEST LUXURY HOTEL<br>IN {{ strtoupper($hotelProfile->name ?? 'INDONESIA') }}
            </h1>

            <!-- CTA Button -->
            <a href="{{ route('rooms') }}" class="inline-block bg-amber-600 text-white px-10 py-4 text-sm tracking-widest uppercase hover:bg-amber-700 transition font-semibold">
                Discover More
            </a>
        </div>

        <!-- Search Bar Floating -->
        <div class="absolute bottom-0 left-0 right-0 transform translate-y-1/2">
            <div class="max-w-6xl mx-auto px-6 lg:px-8">
                <div class="bg-white shadow-2xl rounded-lg p-6">
                    <form action="{{ route('rooms') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        <div>
                            <label class="block text-xs text-gray-500 uppercase tracking-wider mb-2 font-semibold">Check In</label>
                            <input type="date" name="check_in" required 
                                   class="w-full border-b-2 border-gray-200 pb-2 focus:border-amber-600 outline-none transition text-gray-800 font-medium">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 uppercase tracking-wider mb-2 font-semibold">Check Out</label>
                            <input type="date" name="check_out" required 
                                   class="w-full border-b-2 border-gray-200 pb-2 focus:border-amber-600 outline-none transition text-gray-800 font-medium">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 uppercase tracking-wider mb-2 font-semibold">Rooms</label>
                            <select name="rooms" class="w-full border-b-2 border-gray-200 pb-2 focus:border-amber-600 outline-none transition text-gray-800 font-medium bg-transparent">
                                <option value="1">1 Room</option>
                                <option value="2">2 Rooms</option>
                                <option value="3">3 Rooms</option>
                                <option value="4">4 Rooms</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 uppercase tracking-wider mb-2 font-semibold">Guests</label>
                            <select name="guests" class="w-full border-b-2 border-gray-200 pb-2 focus:border-amber-600 outline-none transition text-gray-800 font-medium bg-transparent">
                                <option value="1">1 Guest</option>
                                <option value="2">2 Guests</option>
                                <option value="3">3 Guests</option>
                                <option value="4">4 Guests</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-amber-600 text-white py-3 px-6 text-sm tracking-widest uppercase hover:bg-amber-700 transition font-semibold">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-32 bg-white" id="about">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <p class="text-amber-600 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">About Us</p>
                    <h2 class="font-playfair text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        Welcome to {{ hotel_name() }}
                    </h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        {{ $hotelProfile->description ?? 'Kami menghadirkan pengalaman menginap mewah dengan pelayanan kelas dunia. Setiap detail dirancang untuk kenyamanan dan kepuasan tamu kami.' }}
                    </p>
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-3xl font-bold text-amber-600">15+</h3>
                            <p class="text-gray-500 text-sm">Tahun Pengalaman</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-amber-600">50+</h3>
                            <p class="text-gray-500 text-sm">Kamar Mewah</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-amber-600">100+</h3>
                            <p class="text-gray-500 text-sm">Staff Profesional</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-amber-600">5000+</h3>
                            <p class="text-gray-500 text-sm">Tamu Puas</p>
                        </div>
                    </div>
                    <a href="{{ route('rooms') }}" class="inline-block bg-amber-600 text-white px-8 py-3 text-sm tracking-widest uppercase hover:bg-amber-700 transition font-semibold">
                        View Rooms
                    </a>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Hotel Room" class="rounded-lg shadow-xl w-full h-[500px] object-cover">
                    <div class="absolute -bottom-8 -left-8 bg-amber-600 text-white p-8 rounded-lg shadow-xl hidden md:block">
                        <h3 class="font-playfair text-3xl font-bold">15+</h3>
                        <p class="text-sm tracking-wider uppercase">Years of Excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Room Types Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-amber-600 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Our Rooms</p>
                <h2 class="font-playfair text-4xl md:text-5xl font-bold text-gray-900">Luxury Rooms & Suites</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
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
                            
                            <!-- Facilities -->
                            <div class="flex flex-wrap gap-2 mb-6">
                                @foreach($room->facilities->take(3) as $facility)
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 text-xs rounded-full">{{ $facility->name }}</span>
                                @endforeach
                            </div>

                            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                <span class="text-sm text-gray-500">Max {{ $room->max_guest }} Guests</span>
                                <a href="{{ route('rooms.detail', $room) }}" class="text-amber-600 hover:text-amber-700 text-sm font-semibold tracking-wider uppercase transition">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-3 py-12">Belum ada kamar tersedia.</p>
                @endforelse
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('rooms') }}" class="inline-block bg-amber-600 text-white px-10 py-4 text-sm tracking-widest uppercase hover:bg-amber-700 transition font-semibold">
                    View All Rooms
                </a>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-amber-600 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Hotel Facilities</p>
                <h2 class="font-playfair text-4xl md:text-5xl font-bold text-gray-900">Our Amenities</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @forelse($facilities as $facility)
                    <div class="text-center p-6 rounded-lg hover:bg-amber-50 transition group">
                        @if($facility->icon)
                            <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">{{ $facility->icon }}</div>
                        @elseif($facility->image)
                            <div class="mb-4 flex justify-center">
                                <img src="{{ $facility->image_url }}" alt="{{ $facility->name }}" 
                                     class="h-16 w-16 object-cover rounded-lg group-hover:scale-110 transition-transform">
                            </div>
                        @else
                            <div class="text-5xl mb-4 text-gray-300 group-hover:scale-110 transition-transform">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                        <h3 class="font-semibold text-gray-800 text-sm tracking-wider uppercase">{{ $facility->name }}</h3>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-4 py-12">Belum ada fasilitas tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Testimonials</p>
                <h2 class="font-playfair text-4xl md:text-5xl font-bold">What Our Guests Say</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($testimonials as $testi)
                    <div class="bg-gray-800 p-8 rounded-lg">
                        <div class="flex space-x-1 mb-4">
                            @for($i = 0; $i < $testi->rating; $i++)
                                <svg class="w-5 h-5 text-amber-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-300 italic mb-6 leading-relaxed">"{{ $testi->message }}"</p>
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-amber-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($testi->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-white">{{ $testi->name }}</p>
                                <p class="text-gray-500 text-sm">Verified Guest</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-3">Belum ada testimoni.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-amber-600 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Gallery</p>
                <h2 class="font-playfair text-4xl md:text-5xl font-bold text-gray-900">Our Gallery</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($galleries as $gallery)
                    <div class="relative overflow-hidden rounded-lg group cursor-pointer">
                        <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" 
                             class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                            <span class="text-white opacity-0 group-hover:opacity-100 transition-opacity font-semibold tracking-wider uppercase text-sm">{{ $gallery->title }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-4">Belum ada galeri.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-amber-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <h2 class="font-playfair text-4xl md:text-5xl font-bold mb-6">Ready to Experience Luxury?</h2>
            <p class="text-amber-100 text-lg mb-8">Book your stay now and enjoy the best hospitality in town.</p>
            <a href="{{ route('rooms') }}" class="inline-block bg-white text-amber-600 px-10 py-4 text-sm tracking-widest uppercase hover:bg-gray-100 transition font-bold">
                Book Now
            </a>
        </div>
    </section>
</x-guest-layout>