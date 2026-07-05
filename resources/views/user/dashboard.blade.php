<x-hotel-app-layout>
    <x-slot name="pageTitle">Dashboard Tamu</x-slot>

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl mb-8"
         style="background: linear-gradient(135deg, #1a1208 0%, #3d2a0e 50%, #1a1208 100%);">
        <div class="absolute inset-0 opacity-10"
             style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&q=80'); background-size: cover; background-position: center;"></div>
        <div class="relative p-8 lg:p-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-amber-400 text-xs tracking-widest uppercase font-semibold mb-2">Selamat Datang Kembali</p>
                    <h2 class="font-playfair text-3xl lg:text-4xl font-bold text-white mb-2">
                        {{ Auth::user()->name }}
                    </h2>
                    <p class="text-amber-200/70 text-sm">
                        Nikmati pengalaman menginap mewah bersama kami. Temukan kamar impian Anda.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('rooms') }}"
                       class="inline-flex items-center gap-2 gold-btn px-7 py-3.5 rounded-xl font-semibold text-sm tracking-wider uppercase">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari Kamar
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="card-hotel p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Booking</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalBookings }}</p>
            </div>
        </div>

        <div class="card-hotel p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-gray-800">{{ format_rupiah($totalSpent) }}</p>
            </div>
        </div>

        <div class="card-hotel p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Status Anggota</p>
                <p class="text-lg font-bold text-gray-800">{{ $totalBookings > 1 ? 'Returning Guest' : 'New Guest' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Bookings --}}
        <div class="lg:col-span-2 card-hotel p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-playfair text-xl font-bold text-gray-800">Booking Terbaru</h3>
                <a href="{{ route('user.booking.history') }}"
                   class="text-amber-600 hover:text-amber-700 text-sm font-semibold transition">
                    Lihat Semua →
                </a>
            </div>

            @if($recentBookings->isEmpty())
                <div class="text-center py-10">
                    <div class="w-16 h-16 mx-auto bg-amber-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">Belum ada riwayat booking.</p>
                    <a href="{{ route('rooms') }}"
                       class="mt-3 inline-block text-amber-600 hover:text-amber-700 font-semibold text-sm">
                        Booking Sekarang →
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($recentBookings as $booking)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-amber-200 transition">
                            <div class="w-12 h-12 rounded-xl bg-amber-600 flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-bold text-lg font-playfair">{{ substr($booking->room->roomType->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate">{{ $booking->room->roomType->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $booking->check_in->format('d M') }} - {{ $booking->check_out->format('d M Y') }}
                                    · {{ $booking->total_nights }} malam
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="font-bold text-gray-800 text-sm">{{ format_rupiah($booking->total_price) }}</p>
                                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full
                                    {{ match($booking->status) {
                                        'pending'     => 'bg-yellow-100 text-yellow-700',
                                        'confirmed'   => 'bg-blue-100 text-blue-700',
                                        'checked_in'  => 'bg-green-100 text-green-700',
                                        'checked_out' => 'bg-gray-100 text-gray-700',
                                        'cancelled'   => 'bg-red-100 text-red-700',
                                        default       => 'bg-gray-100 text-gray-700',
                                    } }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </div>
                            @if($booking->status === 'pending' && $booking->payment?->payment_status === 'pending')
                                <a href="{{ route('user.booking.payment', $booking) }}"
                                   class="flex-shrink-0 gold-btn text-xs px-3 py-1.5 rounded-lg font-semibold">
                                    Bayar
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick Links --}}
        <div class="card-hotel p-6">
            <h3 class="font-playfair text-xl font-bold text-gray-800 mb-6">Akses Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('rooms') }}"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <span class="text-gray-700 font-medium text-sm group-hover:text-amber-800 transition">Cari & Booking Kamar</span>
                </a>

                <a href="{{ route('user.booking.history') }}"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                    </div>
                    <span class="text-gray-700 font-medium text-sm group-hover:text-amber-800 transition">Riwayat Booking</span>
                </a>

                <a href="{{ route('restaurant') }}"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="text-gray-700 font-medium text-sm group-hover:text-amber-800 transition">Menu Restoran</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="text-gray-700 font-medium text-sm group-hover:text-amber-800 transition">Edit Profil</span>
                </a>

                <a href="{{ route('faq') }}"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition group">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-gray-700 font-medium text-sm group-hover:text-amber-800 transition">Bantuan & FAQ</span>
                </a>
            </div>
        </div>
    </div>
</x-hotel-app-layout>
