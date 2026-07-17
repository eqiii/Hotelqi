<x-hotel-app-layout>
    <x-slot name="pageTitle">Reservation Voucher</x-slot>

    <div class="max-w-4xl mx-auto">
        {{-- Back Button --}}
        <div class="mb-4">
            <a href="{{ route('user.booking.history') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-amber-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Riwayat
            </a>
        </div>

        {{-- Reservation Voucher Card --}}
        <div class="card-hotel p-8 mb-6">
            {{-- Header --}}
            <div class="text-center border-b border-amber-200 pb-6 mb-6">
                <div class="w-16 h-16 mx-auto bg-amber-600 rounded-full flex items-center justify-center mb-4">
                    <span class="text-white font-playfair font-bold text-xl">{{ substr(hotel_name(), 0, 1) }}</span>
                </div>
                <h1 class="font-playfair text-2xl font-bold text-gray-900">{{ hotel_name() }}</h1>
                <p class="text-amber-600 font-semibold uppercase tracking-widest text-sm mt-1">Reservation Voucher</p>
            </div>

            {{-- Invoice Info --}}
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-8">
                <div>
                    <p class="text-sm text-gray-500">Invoice Number</p>
                    <h3 class="text-xl font-bold text-gray-900">{{ $booking->invoice_number }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Booking Date: {{ $booking->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold {{ $booking->status_badge }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                    @if ($booking->payment)
                        <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold {{ $booking->payment->status_badge }}">
                            {{ strtoupper($booking->payment->payment_status) }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Guest Information --}}
            <div class="border-b border-gray-100 pb-6 mb-6">
                <h2 class="font-playfair text-lg font-bold text-gray-800 mb-4">Guest Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Full Name</p>
                        <p class="font-semibold text-gray-800">{{ $booking->guest->full_name ?? $booking->guest->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Email</p>
                        <p class="font-semibold text-gray-800">{{ $booking->guest->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Phone Number</p>
                        <p class="font-semibold text-gray-800">{{ $booking->guest->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Address</p>
                        <p class="font-semibold text-gray-800">{{ $booking->guest->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Room Information --}}
            <div class="border-b border-gray-100 pb-6 mb-6">
                <h2 class="font-playfair text-lg font-bold text-gray-800 mb-4">Room Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Room Type</p>
                        <p class="font-semibold text-gray-800">{{ $booking->room->roomType->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Room Number</p>
                        <p class="font-semibold text-gray-800">{{ $booking->room->room_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Check-in Date</p>
                        <p class="font-semibold text-gray-800">{{ $booking->check_in->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Check-out Date</p>
                        <p class="font-semibold text-gray-800">{{ $booking->check_out->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Number of Nights</p>
                        <p class="font-semibold text-gray-800">{{ $booking->total_nights }} malam</p>
                    </div>
                </div>
            </div>

            {{-- Payment Information --}}
            @if ($booking->payment)
            <div class="border-b border-gray-100 pb-6 mb-6">
                <h2 class="font-playfair text-lg font-bold text-gray-800 mb-4">Payment Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Room Price</p>
                        <p class="font-semibold text-gray-800">{{ format_rupiah($booking->total_price) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Total Payment</p>
                        <p class="font-bold text-lg text-gray-900">{{ format_rupiah($booking->payment->amount ?? $booking->total_price) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Payment Method</p>
                        <p class="font-semibold text-gray-800">{{ $booking->payment->payment_method ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Payment Date</p>
                        <p class="font-semibold text-gray-800">{{ $booking->payment->paid_at ? $booking->payment->paid_at->format('d M Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Payment Status</p>
                        <p class="font-semibold
                            {{ $booking->payment->payment_status === 'paid' ? 'text-green-600' : ($booking->payment->payment_status === 'failed' ? 'text-red-600' : 'text-yellow-600') }}">
                            {{ ucfirst($booking->payment->payment_status) }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Hotel Information --}}
            @php
                $hotel = \App\Models\HotelProfile::getProfile();
                
                $hasBreakfast = false;
                if (isset($booking->breakfast)) {
                    $hasBreakfast = (bool) $booking->breakfast;
                } elseif (isset($booking->has_breakfast)) {
                    $hasBreakfast = (bool) $booking->has_breakfast;
                }
            @endphp
            <div class="border-b border-gray-100 pb-6 mb-6">
                <h2 class="font-playfair text-lg font-bold text-gray-800 mb-4">Hotel Information</h2>
                
                {{-- Info Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    {{-- 1. WiFi --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-gray-700 text-xs font-semibold flex items-center gap-3">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071a10.5 10.5 0 0114.14 0M1.06 6.06a16.5 16.5 0 0121.88 0"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">WiFi Name</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $hotel?->wifi_name ?? 'Hotel EQI Guest' }}</p>
                        </div>
                    </div>

                    {{-- 2. Password --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-gray-700 text-xs font-semibold flex items-center gap-3">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">WiFi Password</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $hotel?->wifi_password ?? 'HotelEQI2026' }}</p>
                        </div>
                    </div>

                    {{-- 3. Check In --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-gray-700 text-xs font-semibold flex items-center gap-3">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Check In</p>
                            <p class="text-sm font-semibold text-gray-800">14.00 WIB</p>
                        </div>
                    </div>

                    {{-- 4. Check Out --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-gray-700 text-xs font-semibold flex items-center gap-3">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Check Out</p>
                            <p class="text-sm font-semibold text-gray-800">12.00 WIB</p>
                        </div>
                    </div>

                    {{-- 5. Alamat --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-gray-700 text-xs font-semibold flex items-center gap-3">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Alamat Hotel</p>
                            <p class="text-xs font-semibold text-gray-800 leading-relaxed whitespace-pre-line">{{ $hotel?->address ?? "Jl. Raya Hotel EQI No.1\nBogor\nIndonesia" }}</p>
                        </div>
                    </div>

                    {{-- 6. Kontak --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-gray-700 text-xs font-semibold flex items-center gap-3">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Kontak</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $hotel?->phone ?? '+62 812-3456-7890' }}</p>
                        </div>
                    </div>

                    {{-- 7. Breakfast --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-gray-700 text-xs font-semibold flex items-center gap-3">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider mb-1">Breakfast</p>
                            @if ($hasBreakfast)
                                <span class="px-2.5 py-1 bg-green-100 text-green-800 text-[10px] font-bold rounded-full uppercase tracking-wider">Included</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase tracking-wider">Not Included</span>
                            @endif
                        </div>
                    </div>

                    {{-- 8. Email --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-gray-700 text-xs font-semibold flex items-center gap-3">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Email</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $hotel?->email ?? 'reservation@hoteleqi.com' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Facilities and Rules --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Facilities --}}
                    <div>
                        <h3 class="font-playfair text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <span class="w-1.5 h-3 bg-amber-500 rounded-full"></span>
                            Fasilitas Hotel
                        </h3>
                        <ul class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <li class="flex items-center gap-2 font-medium">
                                <span class="text-green-500 font-bold">✓</span> Free WiFi
                            </li>
                            <li class="flex items-center gap-2 font-medium">
                                <span class="text-green-500 font-bold">✓</span> Swimming Pool
                            </li>
                            <li class="flex items-center gap-2 font-medium">
                                <span class="text-green-500 font-bold">✓</span> Restaurant
                            </li>
                            <li class="flex items-center gap-2 font-medium">
                                <span class="text-green-500 font-bold">✓</span> Parking Area
                            </li>
                            <li class="flex items-center gap-2 font-medium">
                                <span class="text-green-500 font-bold">✓</span> 24 Hours Reception
                            </li>
                            <li class="flex items-center gap-2 font-medium">
                                <span class="text-green-500 font-bold">✓</span> Room Service
                            </li>
                        </ul>
                    </div>

                    {{-- Rules --}}
                    <div>
                        <h3 class="font-playfair text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <span class="w-1.5 h-3 bg-amber-500 rounded-full"></span>
                            Hotel Rules
                        </h3>
                        <ul class="list-disc pl-4 text-xs text-gray-600 space-y-1">
                            <li>Check-in mulai pukul 14.00 WIB.</li>
                            <li>Check-out maksimal pukul 12.00 WIB.</li>
                            <li>Dilarang merokok di dalam kamar.</li>
                            <li>Dilarang membawa hewan peliharaan.</li>
                            <li>Tunjukkan QR Code saat check-in.</li>
                            <li>WiFi gratis tersedia di seluruh area hotel.</li>
                            <li>Kehilangan kartu akses dikenakan biaya penggantian.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Booking Timeline --}}
            <div class="border-b border-gray-100 pb-6 mb-6">
                <h2 class="font-playfair text-lg font-bold text-gray-800 mb-4">Booking Timeline</h2>
                <div class="flex flex-col gap-0">
                    @php
                        $statusSteps = [
                            'pending'      => 'Pending Payment',
                            'confirmed'    => 'Confirmed',
                            'checked_in'   => 'Checked In',
                            'checked_out'  => 'Checked Out',
                        ];
                        $currentStep = $booking->status;
                        $stepKeys = array_keys($statusSteps);
                        $currentIdx = array_search($currentStep, $stepKeys);
                    @endphp

                    @foreach ($statusSteps as $key => $label)
                        @php
                            $stepIdx = array_search($key, $stepKeys);
                            $isComplete = $stepIdx <= $currentIdx;
                            $isCurrent = $key === $currentStep;
                        @endphp

                        <div class="flex items-start gap-4">
                            {{-- Step Indicator --}}
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                    {{ $isCurrent ? 'bg-amber-500 text-white ring-4 ring-amber-100' : ($isComplete ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400') }}">
                                    @if ($isComplete && !$isCurrent)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </div>
                                @if (!$loop->last)
                                    <div class="w-0.5 h-8 {{ $stepIdx < $currentIdx ? 'bg-green-400' : 'bg-gray-200' }}"></div>
                                @endif
                            </div>
                            {{-- Step Label --}}
                            <div class="pb-6">
                                <p class="font-semibold {{ $isCurrent ? 'text-amber-700' : ($isComplete ? 'text-gray-800' : 'text-gray-400') }}">
                                    {{ $label }}
                                </p>
                                @if ($isCurrent && $booking->status === 'pending')
                                    <p class="text-xs text-gray-500">Menunggu pembayaran</p>
                                @elseif ($isCurrent && $booking->status === 'confirmed')
                                    <p class="text-xs text-green-600">Pembayaran diterima. Siap untuk check-in.</p>
                                @elseif ($isCurrent && $booking->status === 'checked_in')
                                    <p class="text-xs text-green-600">Tamu sedang menginap.</p>
                                @elseif ($isCurrent && $booking->status === 'checked_out')
                                    <p class="text-xs text-gray-500">Terima kasih telah menginap.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- QR Code Section --}}
            <div class="text-center py-6">
                <h2 class="font-playfair text-lg font-bold text-gray-800 mb-4">Booking QR Code</h2>
                <p class="text-sm text-gray-500 mb-4">Tunjukkan QR ini kepada resepsionis saat check-in</p>
                <div class="inline-block bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                    {!! QrCode::size(180)->generate($booking->id) !!}
                </div>
                <p class="text-xs text-gray-400 mt-3">Booking ID: #{{ $booking->id }}</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap gap-3 justify-center mb-8">
            @if ($booking->payment && $booking->payment->isPaid())
                <a href="{{ route('user.booking.pdf', $booking) }}"
                   class="gold-btn inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download PDF
                </a>
            @endif
            <a href="{{ route('user.booking.history') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                Kembali ke Riwayat
            </a>
        </div>
    </div>
</x-hotel-app-layout>
