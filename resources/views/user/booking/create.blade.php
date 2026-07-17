<x-hotel-app-layout>
    <x-slot name="pageTitle">Booking Room</x-slot>

    <div class="max-w-5xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-amber-600/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-playfair text-3xl font-bold text-gray-900">Booking Room</h1>
                    <p class="text-amber-600 font-semibold text-lg">{{ $roomType->name }}</p>
                </div>
            </div>
            <p class="text-gray-500 text-sm mt-2 ml-[3.25rem]">Lengkapi data berikut untuk melanjutkan ke pembayaran.</p>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold text-sm">Terdapat {{ $errors->count() }} kesalahan pada form</span>
                </div>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php $guest = Auth::user()->guest ?? null; @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- === FORM CARD === --}}
            <div class="lg:col-span-2">
                <form action="{{ route('user.booking.store') }}" method="POST" enctype="multipart/form-data" class="card-hotel p-6 lg:p-8">
                    @csrf
                    <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                    {{-- Section Title --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-8 h-8 rounded-lg bg-amber-600 flex items-center justify-center text-white text-sm font-bold">1</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Informasi Tamu</h3>
                            <p class="text-xs text-gray-400">Data diri pemesan kamar</p>
                        </div>
                    </div>

                    {{-- Row: Full Name & Phone --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" id="full_name" name="full_name" required
                                value="{{ old('full_name', $guest->full_name ?? Auth::user()->name) }}"
                                placeholder="Masukkan nama lengkap"
                                class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition @error('full_name') border-red-300 bg-red-50 @enderror">
                            @error('full_name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                            <input type="text" id="phone" name="phone" required
                                value="{{ old('phone', $guest->phone ?? '') }}"
                                placeholder="Contoh: 081234567890"
                                class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition @error('phone') border-red-300 bg-red-50 @enderror">
                            @error('phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row: KTP & Avatar Upload --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="ktp_number" class="block text-sm font-medium text-gray-700 mb-1.5">KTP Number <span class="text-red-500">*</span></label>
                            <input type="text" id="ktp_number" name="ktp_number" required
                                value="{{ old('ktp_number', $guest->ktp_number ?? '') }}"
                                placeholder="Masukkan 16 digit KTP"
                                class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition @error('ktp_number') border-red-300 bg-red-50 @enderror">
                            @error('ktp_number')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Avatar <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <div class="relative">
                                <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
                                <label for="avatar"
                                    class="flex flex-col items-center justify-center w-full h-[42px] rounded-lg border-2 border-dashed border-gray-200 bg-gray-50 hover:border-amber-400 hover:bg-amber-50/50 cursor-pointer transition group">
                                    <div class="flex items-center gap-2 text-sm text-gray-500 group-hover:text-amber-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span id="avatarLabel">Upload Avatar</span>
                                    </div>
                                </label>
                            </div>
                            @error('avatar')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row: Address (Full Width) --}}
                    <div class="mb-5">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                        <textarea id="address" name="address" rows="2"
                            placeholder="Masukkan alamat lengkap"
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition @error('address') border-red-300 bg-red-50 @enderror">{{ old('address', $guest->address ?? '') }}</textarea>
                        @error('address')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Section Divider --}}
                    <div class="flex items-center gap-3 mb-6 mt-8 pb-4 border-b border-gray-100">
                        <div class="w-8 h-8 rounded-lg bg-amber-600 flex items-center justify-center text-white text-sm font-bold">2</div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Detail Menginap</h3>
                            <p class="text-xs text-gray-400">Pilih tanggal check-in dan check-out</p>
                        </div>
                    </div>

                    {{-- Row: Check In & Check Out --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="check_in" class="block text-sm font-medium text-gray-700 mb-1.5">Check In <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="date" id="check_in" name="check_in" required value="{{ old('check_in') }}"
                                    class="w-full rounded-lg border border-gray-200 pl-10 pr-4 py-2.5 text-sm text-gray-900 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition @error('check_in') border-red-300 bg-red-50 @enderror">
                            </div>
                            @error('check_in')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="check_out" class="block text-sm font-medium text-gray-700 mb-1.5">Check Out <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="date" id="check_out" name="check_out" required value="{{ old('check_out') }}"
                                    class="w-full rounded-lg border border-gray-200 pl-10 pr-4 py-2.5 text-sm text-gray-900 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition @error('check_out') border-red-300 bg-red-50 @enderror">
                            </div>
                            @error('check_out')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="mb-5">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Notes <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea id="notes" name="notes" rows="3"
                            placeholder="Permintaan khusus atau catatan tambahan..."
                            class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition @error('notes') border-red-300 bg-red-50 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <button type="submit" id="submitBtn"
                            class="w-full gold-btn rounded-lg px-6 py-3.5 text-sm font-semibold tracking-wider uppercase flex items-center justify-center gap-3 transition-all duration-300">
                            <span id="btnText">Continue to Payment</span>
                            <svg id="btnSpinner" class="hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- === ROOM SUMMARY CARD === --}}
            <div class="lg:col-span-1">
                <div class="card-hotel overflow-hidden sticky top-8">
                    {{-- Room Image --}}
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $roomType->image_url }}" alt="{{ $roomType->name }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h4 class="text-white font-playfair text-xl font-bold">{{ $roomType->name }}</h4>
                            <div class="flex items-center gap-1 text-amber-400 text-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-xs font-medium">{{ $roomType->available_rooms_count }} kamar tersedia</span>
                            </div>
                        </div>
                    </div>

                    {{-- Room Details --}}
                    <div class="p-5 space-y-4">
                        {{-- Price --}}
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Harga/malam</span>
                            <span class="text-2xl font-bold text-amber-600">{{ format_rupiah($roomType->base_price) }}</span>
                        </div>

                        {{-- Capacity --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Kapasitas</span>
                            <span class="text-sm font-semibold text-gray-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Max {{ $roomType->max_guest }} Tamu
                            </span>
                        </div>

                        {{-- Bed --}}
                        @if($roomType->total_bed)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Tempat Tidur</span>
                            <span class="text-sm font-semibold text-gray-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v11a1 1 0 001 1h16a1 1 0 001-1V7M3 7V5a1 1 0 011-1h16a1 1 0 011 1v2M3 7h18"/>
                                </svg>
                                {{ $roomType->total_bed }} Kasur
                            </span>
                        </div>
                        @endif

                        {{-- Status --}}
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="text-sm text-gray-500">Status Kamar</span>
                            @if($roomType->available_rooms_count > 0)
                                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-600">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-600">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    Penuh
                                </span>
                            @endif
                        </div>

                        {{-- Facilities --}}
                        @if($roomType->facilities->count() > 0)
                        <div class="pt-3 border-t border-gray-100">
                            <span class="text-sm text-gray-500 block mb-2">Fasilitas Kamar</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($roomType->facilities->take(4) as $facility)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-full">
                                        @if($facility->icon)
                                            {{ $facility->icon }}
                                        @endif
                                        {{ $facility->name }}
                                    </span>
                                @endforeach
                                @if($roomType->facilities->count() > 4)
                                    <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded-full">
                                        +{{ $roomType->facilities->count() - 4 }} lainnya
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Info card --}}
                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-amber-800 leading-relaxed">
                            Pembayaran dilakukan melalui <strong>Midtrans</strong> (Virtual Account, Kartu Kredit, atau metode lainnya). 
                            Booking akan dikonfirmasi setelah pembayaran berhasil.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-hotel-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Avatar upload label update
        document.getElementById('avatar')?.addEventListener('change', function(e) {
            const label = document.getElementById('avatarLabel');
            if (this.files && this.files[0]) {
                label.textContent = this.files[0].name;
            } else {
                label.textContent = 'Upload Avatar';
            }
        });

        // Loading state on form submit
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');
            if (btn && text && spinner) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
                btn.style.cursor = 'not-allowed';
                text.textContent = 'Processing...';
                spinner.classList.remove('hidden');
            }
        });

        // Set min date for check_in to today
        const today = new Date().toISOString().split('T')[0];
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');
        if (checkIn) checkIn.min = today;
        if (checkOut) checkOut.min = today;

        // Auto-update check_out min when check_in changes
        checkIn?.addEventListener('change', function() {
            if (checkOut) {
                checkOut.min = this.value;
                if (checkOut.value && checkOut.value < this.value) {
                    checkOut.value = '';
                }
            }
        });
    });
</script>
