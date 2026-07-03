<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Tamu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Success Message Alert -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6 rounded shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 border border-gray-100">
                <div class="p-8 text-gray-900 flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600">Terima kasih telah memilih hotel kami untuk tempat menginap Anda. Di sini Anda bisa mengelola pesanan kamar Anda.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('rooms') }}" class="inline-block bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-semibold transition text-sm tracking-wider uppercase">
                            Cari Kamar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats & Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Action 1: Booking History -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="text-amber-600 text-3xl mb-4">📅</div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Riwayat Booking</h4>
                    <p class="text-sm text-gray-600 mb-4">Lihat status pesanan, faktur, dan rincian kunjungan Anda sebelumnya.</p>
                    <a href="{{ route('user.booking.history') }}" class="text-amber-600 hover:text-amber-700 font-semibold text-sm transition">
                        Buka Riwayat →
                    </a>
                </div>

                <!-- Action 2: Edit Profile -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="text-amber-600 text-3xl mb-4">👤</div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Profil Saya</h4>
                    <p class="text-sm text-gray-600 mb-4">Perbarui informasi kontak, alamat email, dan kata sandi akun Anda.</p>
                    <a href="{{ route('profile.edit') }}" class="text-amber-600 hover:text-amber-700 font-semibold text-sm transition">
                        Buka Profil →
                    </a>
                </div>

                <!-- Action 3: Restaurant Orders -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="text-amber-600 text-3xl mb-4">🍽️</div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Pesanan Restoran</h4>
                    <p class="text-sm text-gray-600 mb-4">Kelola pesanan makanan Anda selama menginap dengan cepat dan mudah.</p>
                    <a href="{{ route('user.restaurant.orders') }}" class="text-amber-600 hover:text-amber-700 font-semibold text-sm transition">
                        Lihat Pesanan →
                    </a>
                </div>

                <!-- Action 4: Contact Service -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="text-amber-600 text-3xl mb-4">🛎️</div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">Layanan Pelanggan</h4>
                    <p class="text-sm text-gray-600 mb-4">Butuh bantuan? Tim kami siap melayani Anda 24 jam penuh.</p>
                    <a href="{{ route('faq') }}" class="text-amber-600 hover:text-amber-700 font-semibold text-sm transition">
                        Lihat FAQ →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
