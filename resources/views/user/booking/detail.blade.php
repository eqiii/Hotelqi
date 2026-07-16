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
