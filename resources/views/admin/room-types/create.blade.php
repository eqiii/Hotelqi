@extends('layouts.admin')

@section('pageTitle')
    Create Room Type
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto">

            {{-- Header --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.room-types.index') }}"
                       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-amber-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Kembali
                    </a>
                </div>
                <div class="mt-4">
                    <h1 class="font-playfair text-2xl font-bold text-[#2E2E2E]">Create Room Type</h1>
                    <p class="text-gray-500 text-sm mt-1">Tambahkan tipe kamar baru untuk hotel.</p>
                </div>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <form method="POST" action="{{ route('admin.room-types.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Name --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Room Type <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition @error('name') border-red-400 @else border-gray-300 @enderror"
                                   placeholder="Contoh: Deluxe Room">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Base Price --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Dasar <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold text-sm">Rp</span>
                                <input type="number" name="base_price" value="{{ old('base_price') }}" required step="0.01" min="0"
                                       class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition @error('base_price') border-red-400 @else border-gray-300 @enderror"
                                       placeholder="450000">
                            </div>
                            @error('base_price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Max Guest --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Max Guest</label>
                            <input type="number" name="max_guest" value="{{ old('max_guest') }}" min="1"
                                   class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition @error('max_guest') border-red-400 @else border-gray-300 @enderror"
                                   placeholder="2">
                            @error('max_guest') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Total Bed --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Bed</label>
                            <input type="number" name="total_bed" value="{{ old('total_bed') }}" min="1"
                                   class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition @error('total_bed') border-red-400 @else border-gray-300 @enderror"
                                   placeholder="1">
                            @error('total_bed') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Image --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar <span class="text-red-500">*</span></label>
                            <input type="file" name="image" accept="image/*"
                                   class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 @error('image') border-red-400 @else border-gray-300 @enderror">
                            @error('image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" rows="4"
                                      class="w-full px-4 py-2.5 border rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition @error('description') border-red-400 @else border-gray-300 @enderror"
                                      placeholder="Deskripsi lengkap tentang tipe kamar...">{{ old('description') }}</textarea>
                            @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan
                        </button>
                        <a href="{{ route('admin.room-types.index') }}"
                           class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
