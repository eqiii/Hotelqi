@extends('layouts.admin')

@section('pageTitle')
    Guest Management
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
                        <h1 class="font-playfair text-2xl font-bold text-[#2E2E2E]">Guest Management</h1>
                        <p class="text-gray-500 text-sm mt-1">Kelola seluruh data tamu hotel</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-3">
                            <p class="text-xs text-amber-600 font-semibold uppercase tracking-wider">Total Tamu</p>
                            <p class="text-2xl font-bold text-amber-700">{{ $guests->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 mb-6">
                <form method="GET" action="{{ route('admin.guests.index') }}" class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama / email / telepon..."
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                        </div>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('admin.guests.index') }}"
                           class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-600 hover:bg-gray-50 transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                @if($guests->isEmpty())
                    {{-- Empty State --}}
                    <div class="text-center py-16">
                        <div class="w-20 h-20 mx-auto bg-amber-50 rounded-full flex items-center justify-center mb-5">
                            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.761 0 5.312.84 7.379 2.273M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-playfair text-xl font-bold text-gray-700 mb-2">Belum Ada Tamu</h3>
                        <p class="text-gray-500 text-sm">Belum ada data tamu yang tercatat di sistem.</p>
                    </div>
                @else
                    {{-- Desktop Table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-[#1D140B]">
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Nama</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Email</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Phone</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Address</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">KTP/NIK</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($guests as $g)
                                    <tr class="hover:bg-orange-50 transition">
                                        {{-- Nama with Avatar --}}
                                        <td class="px-5 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-amber-700 font-bold text-sm">
                                                        {{ substr($g->user->name ?? '?', 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800 text-sm">{{ $g->user->name ?? '-' }}</p>
                                                    <p class="text-xs text-gray-400">ID: #{{ $g->id }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Email --}}
                                        <td class="px-5 py-5">
                                            <p class="text-sm text-gray-700">{{ $g->user->email ?? '-' }}</p>
                                        </td>

                                        {{-- Phone --}}
                                        <td class="px-5 py-5">
                                            <p class="text-sm text-gray-700">{{ $g->phone ?? '-' }}</p>
                                        </td>

                                        {{-- Address --}}
                                        <td class="px-5 py-5">
                                            <p class="text-sm text-gray-700 max-w-[200px] truncate">{{ $g->address ?? '-' }}</p>
                                        </td>

                                        {{-- KTP/NIK --}}
                                        <td class="px-5 py-5">
                                            @if($g->ktp_number)
                                                <span class="text-sm font-mono text-gray-700">{{ $g->ktp_number }}</span>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-5 py-5">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.guests.show', $g) }}"
                                                   class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-semibold transition shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Detail
                                                </a>
                                                <a href="{{ route('admin.guests.edit', $g) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.guests.destroy', $g) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data tamu ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold border border-red-200 text-red-600 hover:bg-red-50 transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards --}}
                    <div class="md:hidden divide-y divide-gray-100">
                        @foreach($guests as $g)
                            <div class="p-4 hover:bg-orange-50 transition">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-amber-700 font-bold">{{ substr($g->user->name ?? '?', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $g->user->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $g->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                                    <div>
                                        <p class="text-xs text-gray-400">Phone</p>
                                        <p class="font-medium text-gray-700">{{ $g->phone ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400">KTP</p>
                                        <p class="font-medium text-gray-700">{{ $g->ktp_number ?? '-' }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-xs text-gray-400">Address</p>
                                        <p class="font-medium text-gray-700">{{ $g->address ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.guests.show', $g) }}"
                                       class="inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.guests.edit', $g) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.guests.destroy', $g) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data tamu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-200 text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="px-5 py-4 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p class="text-sm text-gray-500">
                                Menampilkan {{ $guests->firstItem() ?? 0 }} - {{ $guests->lastItem() ?? 0 }} dari {{ $guests->total() }} tamu
                            </p>
                            <div class="flex items-center gap-1">
                                @if ($guests->onFirstPage())
                                    <span class="px-3 py-1.5 rounded-lg text-xs text-gray-400 bg-gray-50 border border-gray-200">Prev</span>
                                @else
                                    <a href="{{ $guests->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-amber-50 hover:border-amber-300 transition">Prev</a>
                                @endif

                                @foreach ($guests->getUrlRange(max(1, $guests->currentPage() - 2), min($guests->lastPage(), $guests->currentPage() + 2)) as $page => $url)
                                    <a href="{{ $url }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                                        {{ $page == $guests->currentPage()
                                            ? 'bg-amber-500 text-white shadow-sm'
                                            : 'text-gray-700 bg-white border border-gray-200 hover:bg-amber-50 hover:border-amber-300' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach

                                @if ($guests->hasMorePages())
                                    <a href="{{ $guests->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-amber-50 hover:border-amber-300 transition">Next</a>
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
