@extends('layouts.admin')

@section('pageTitle')
    Bookings
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto">

            {{-- Alert --}}
            @if (session('status'))
                <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 text-green-700 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('status') }}</p>
                </div>
            @endif

            {{-- Header Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="font-playfair text-2xl font-bold text-[#2E2E2E]">Bookings</h1>
                        <p class="text-gray-500 text-sm mt-1">Kelola seluruh reservasi hotel</p>
                    </div>
                    <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-3">
                        <p class="text-xs text-amber-600 font-semibold uppercase tracking-wider">Total Booking</p>
                        <p class="text-2xl font-bold text-amber-700">{{ $bookings->total() }}</p>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 mb-6">
                <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex flex-wrap items-center gap-4">
                    <div>
                        <select name="status" onchange="this.form.submit()"
                                class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition bg-white cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                            <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    @if(request('status'))
                        <a href="{{ route('admin.bookings.index') }}"
                           class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">Reset</a>
                    @endif
                </form>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                @if($bookings->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-20 h-20 mx-auto bg-amber-50 rounded-full flex items-center justify-center mb-5">
                            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="font-playfair text-xl font-bold text-gray-700 mb-2">Belum Ada Booking</h3>
                        <p class="text-gray-500 text-sm">Belum ada reservasi yang tercatat di sistem.</p>
                    </div>
                @else
                    {{-- Desktop Table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-[#1D140B]">
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Invoice</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Guest</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Room</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Status</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($bookings as $b)
                                    <tr class="hover:bg-orange-50 transition">
                                        <td class="px-5 py-5">
                                            <p class="font-bold text-gray-800 text-sm">{{ $b->invoice_number }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $b->created_at->format('d M Y') }}</p>
                                        </td>
                                        <td class="px-5 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-amber-700 font-bold text-sm">{{ substr($b->guest->user->name ?? '?', 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800 text-sm">{{ $b->guest->user->name ?? '-' }}</p>
                                                    <p class="text-xs text-gray-400">{{ $b->guest->user->email ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-5">
                                            <p class="font-semibold text-gray-800 text-sm">{{ $b->room->roomType->name ?? '-' }}</p>
                                            <p class="text-xs text-gray-400">Room {{ $b->room->room_number ?? '-' }}</p>
                                        </td>
                                        <td class="px-5 py-5">
                                            @php
                                                $badge = match($b->status) {
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                                    'checked_in' => 'bg-green-100 text-green-700',
                                                    'checked_out' => 'bg-gray-200 text-gray-600',
                                                    'cancelled' => 'bg-red-100 text-red-700',
                                                    default => 'bg-gray-100 text-gray-600',
                                                };
                                            @endphp
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                                {{ ucfirst(str_replace('_', ' ', $b->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-5">
                                            <a href="{{ route('admin.bookings.show', $b) }}"
                                               class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-semibold transition shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($bookings as $b)
                            <div class="p-4 hover:bg-orange-50 transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $b->invoice_number }}</p>
                                        <p class="text-xs text-gray-400">{{ $b->created_at->format('d M Y') }}</p>
                                    </div>
                                    @php
                                        $badge = match($b->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'confirmed' => 'bg-blue-100 text-blue-700',
                                            'checked_in' => 'bg-green-100 text-green-700',
                                            'checked_out' => 'bg-gray-200 text-gray-600',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $b->status)) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-amber-700 font-bold text-sm">{{ substr($b->guest->user->name ?? '?', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $b->guest->user->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $b->guest->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-400">Room</p>
                                        <p class="font-medium text-gray-700 text-sm">{{ $b->room->roomType->name ?? '-' }} ({{ $b->room->room_number ?? '-' }})</p>
                                    </div>
                                    <a href="{{ route('admin.bookings.show', $b) }}"
                                       class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="px-5 py-4 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p class="text-sm text-gray-500">
                                Menampilkan {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} dari {{ $bookings->total() }} booking
                            </p>
                            <div class="flex items-center gap-1">
                                @if ($bookings->onFirstPage())
                                    <span class="px-3 py-1.5 rounded-lg text-xs text-gray-400 bg-gray-50 border border-gray-200">Prev</span>
                                @else
                                    <a href="{{ $bookings->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-amber-50 hover:border-amber-300 transition">Prev</a>
                                @endif
                                @foreach ($bookings->getUrlRange(max(1, $bookings->currentPage() - 2), min($bookings->lastPage(), $bookings->currentPage() + 2)) as $page => $url)
                                    <a href="{{ $url }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                                        {{ $page == $bookings->currentPage() ? 'bg-amber-500 text-white shadow-sm' : 'text-gray-700 bg-white border border-gray-200 hover:bg-amber-50 hover:border-amber-300' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach
                                @if ($bookings->hasMorePages())
                                    <a href="{{ $bookings->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-amber-50 hover:border-amber-300 transition">Next</a>
                                @else
                                    <span class="px-3 py-1.5 rounded-lg text-xs text-gray-400 bg-gray-50 border border-gray-200">Next</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
