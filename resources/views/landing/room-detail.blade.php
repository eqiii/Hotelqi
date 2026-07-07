<x-guest-layout title="{{ $roomType->name }}">
    <!-- Page Header -->
    <section class="bg-gray-900 text-white py-24 text-center">
        <p class="text-amber-400 text-sm tracking-[0.3em] uppercase mb-4 font-semibold">Room Details</p>
        <h1 class="font-playfair text-5xl font-bold">{{ $roomType->name }}</h1>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Image -->
                <div>
                    <img src="{{ $roomType->image_url }}" alt="{{ $roomType->name }}"
                         class="w-full h-[500px] object-cover rounded-lg shadow-xl">
                </div>

                <!-- Details -->
                <div>
                    <div class="mb-6">
                        <span class="bg-amber-100 text-amber-800 px-4 py-2 text-sm font-semibold rounded-full">
                            {{ format_rupiah($roomType->base_price) }} / night
                        </span>
                    </div>

                    <h2 class="font-playfair text-3xl font-bold text-gray-900 mb-4">{{ $roomType->name }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-8">{{ $roomType->description }}</p>

                    <!-- Room Info -->
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Max Guests</p>
                            <p class="text-xl font-bold text-gray-900">{{ $roomType->max_guest }} Persons</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Total Beds</p>
                            <p class="text-xl font-bold text-gray-900">{{ $roomType->total_bed }} Bed(s)</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Available Rooms</p>
                            <p class="text-xl font-bold text-amber-600">{{ $roomType->available_rooms_count }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500">Total Rooms</p>
                            <p class="text-xl font-bold text-gray-900">{{ $roomType->total_rooms_count }}</p>
                        </div>
                    </div>

                    <!-- Facilities -->
                    <h3 class="font-playfair text-xl font-bold text-gray-900 mb-4">Facilities</h3>
                    <div class="flex flex-wrap gap-3 mb-8">
                        @foreach($roomType->facilities as $facility)
                            <span class="bg-amber-50 text-amber-800 px-4 py-2 text-sm rounded-full font-medium">{{ $facility->name }}</span>
                        @endforeach
                    </div>

                    <!-- Book Now CTA -> opens dedicated booking page -->
                    @auth
                        <a href="{{ route('user.booking.create', $roomType) }}" class="inline-block w-full text-center bg-amber-600 text-white py-3 text-sm tracking-widest uppercase hover:bg-amber-700 transition font-semibold rounded-lg">
                            Book Now
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-amber-600 text-white py-3 text-center text-sm tracking-widest uppercase hover:bg-amber-700 transition font-semibold rounded-lg">
                            Login to Book
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
