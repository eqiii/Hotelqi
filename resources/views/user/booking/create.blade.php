<x-hotel-app-layout>
    <x-slot name="pageTitle">Buat Booking - {{ $roomType->name }}</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="section-shell">
            <h2 class="font-playfair text-2xl font-bold text-[#f7e7c9] mb-2">Booking: {{ $roomType->name }}</h2>
            <p class="text-sm text-[#c7b093] mb-4">Lengkapi tanggal check-in dan check-out untuk melanjutkan ke
                pembayaran.</p>

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-[#9d7b31]/30 bg-[#3f2b1d]/90 px-4 py-3 text-[#f5d7a2]">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php $guest = Auth::user()->guest ?? null; @endphp
            <form action="{{ route('user.booking.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                {{-- Guest details section --}}
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-[#f7e7c9] mb-3">Guest Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-[#f7e7c9] mb-1">Full Name</label>
                            <input type="text" name="full_name" required
                                value="{{ old('full_name', $guest->full_name ?? Auth::user()->name) }}"
                                class="form-field">
                            @error('full_name')
                                <p class="text-sm text-[#f5d7a2] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#f7e7c9] mb-1">Phone</label>
                            <input type="text" name="phone" required
                                value="{{ old('phone', $guest->phone ?? '') }}" class="form-field">
                            @error('phone')
                                <p class="text-sm text-[#f5d7a2] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-[#f7e7c9] mb-1">KTP Number</label>
                            <input type="text" name="ktp_number" required
                                value="{{ old('ktp_number', $guest->ktp_number ?? '') }}" class="form-field">
                            @error('ktp_number')
                                <p class="text-sm text-[#f5d7a2] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-[#f7e7c9] mb-1">Address</label>
                        <textarea name="address" rows="2" class="form-field">{{ old('address', $guest->address ?? '') }}</textarea>
                        @error('address')
                            <p class="text-sm text-[#f5d7a2] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#f7e7c9] mb-1">Avatar (optional)</label>
                        <input type="file" name="avatar" accept="image/*" class="w-full">
                        @error('avatar')
                            <p class="text-sm text-[#f5d7a2] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#f7e7c9] mb-1">Check In</label>
                        <input type="date" name="check_in" required value="{{ old('check_in') }}"
                            class="form-field">
                        @error('check_in')
                            <p class="text-sm text-[#f5d7a2] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#f7e7c9] mb-1">Check Out</label>
                        <input type="date" name="check_out" required value="{{ old('check_out') }}"
                            class="form-field">
                        @error('check_out')
                            <p class="text-sm text-[#f5d7a2] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#f7e7c9] mb-1">Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="form-field">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-sm text-[#f5d7a2] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="gold-btn w-full px-6 py-3">Continue to Payment</button>
                </div>
            </form>
        </div>

        <div class="text-sm text-center text-[#c7b093]">Jika terjadi kegagalan ketersediaan kamar saat membuat booking,
            Anda akan melihat pesan yang jelas pada form ini.</div>
    </div>
</x-hotel-app-layout>
