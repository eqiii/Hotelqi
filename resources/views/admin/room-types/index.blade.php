@extends('layouts.admin')

@section('pageTitle')
    Room Types
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
                        <h1 class="font-playfair text-2xl font-bold text-[#2E2E2E]">Room Types</h1>
                        <p class="text-gray-500 text-sm mt-1">Kelola seluruh tipe kamar hotel.</p>
                    </div>
                    <a href="{{ route('admin.room-types.create') }}"
                       class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Room Type
                    </a>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                @if($roomTypes->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-20 h-20 mx-auto bg-amber-50 rounded-full flex items-center justify-center mb-5">
                            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </div>
                        <h3 class="font-playfair text-xl font-bold text-gray-700 mb-2">Belum ada tipe kamar.</h3>
                        <p class="text-gray-500 text-sm mb-6">Tambahkan Room Type pertama.</p>
                        <a href="{{ route('admin.room-types.create') }}"
                           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Room Type
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-[#24170F]">
                                    <th class="px-5 py-4 text-center text-xs font-semibold text-amber-400 uppercase tracking-widest w-12">No</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Nama Room Type</th>
                                    <th class="px-5 py-4 text-left text-xs font-semibold text-amber-400 uppercase tracking-widest">Deskripsi</th>
                                    <th class="px-5 py-4 text-center text-xs font-semibold text-amber-400 uppercase tracking-widest">Harga Dasar</th>
                                    <th class="px-5 py-4 text-center text-xs font-semibold text-amber-400 uppercase tracking-widest">Max Guest</th>
                                    <th class="px-5 py-4 text-center text-xs font-semibold text-amber-400 uppercase tracking-widest">Total Bed</th>
                                    <th class="px-5 py-4 text-center text-xs font-semibold text-amber-400 uppercase tracking-widest">Jumlah Room</th>
                                    <th class="px-5 py-4 text-center text-xs font-semibold text-amber-400 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($roomTypes as $index => $type)
                                    <tr class="hover:bg-orange-50 transition">
                                        <td class="px-5 py-5 text-center text-sm text-gray-600">{{ $roomTypes->firstItem() + $index }}</td>
                                        <td class="px-5 py-5">
                                            <div class="flex items-center gap-3">
                                                @if($type->image)
                                                    <img src="{{ $type->image_url }}" alt="{{ $type->name }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                                @else
                                                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                                                        <span class="text-amber-700 font-bold text-sm">{{ substr($type->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <p class="font-semibold text-gray-800 text-sm">{{ $type->name }}</p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-5">
                                            @if($type->description)
                                                <p class="text-sm text-gray-600 line-clamp-2 max-w-[250px]">{{ $type->description }}</p>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-5 text-center">
                                            <p class="font-semibold text-gray-800 text-sm">{{ format_rupiah($type->base_price ?? 0) }}</p>
                                        </td>
                                        <td class="px-5 py-5 text-center text-sm text-gray-700">{{ $type->max_guest ?? '-' }}</td>
                                        <td class="px-5 py-5 text-center text-sm text-gray-700">{{ $type->total_bed ?? '-' }}</td>
                                        <td class="px-5 py-5 text-center">
                                            <span class="font-semibold text-gray-800 text-sm">{{ $type->total_rooms_count }}</span>
                                        </td>
                                        <td class="px-5 py-5 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.room-types.edit', $type) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold border border-blue-200 text-blue-600 hover:bg-blue-50 transition"
                                                   title="Edit">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <button type="button"
                                                        onclick="openDeleteModal({{ $type->id }}, '{{ $type->name }}')"
                                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold border border-red-200 text-red-600 hover:bg-red-50 transition"
                                                        title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-5 py-4 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p class="text-sm text-gray-500">
                                Menampilkan {{ $roomTypes->firstItem() ?? 0 }} - {{ $roomTypes->lastItem() ?? 0 }} dari {{ $roomTypes->total() }} tipe kamar
                            </p>
                            <div class="flex items-center gap-1">
                                @if ($roomTypes->onFirstPage())
                                    <span class="px-3 py-1.5 rounded-lg text-xs text-gray-400 bg-gray-50 border border-gray-200">Prev</span>
                                @else
                                    <a href="{{ $roomTypes->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-amber-50 hover:border-amber-300 transition">Prev</a>
                                @endif
                                @foreach ($roomTypes->getUrlRange(max(1, $roomTypes->currentPage() - 2), min($roomTypes->lastPage(), $roomTypes->currentPage() + 2)) as $page => $url)
                                    <a href="{{ $url }}"
                                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                                        {{ $page == $roomTypes->currentPage() ? 'bg-amber-500 text-white shadow-sm' : 'text-gray-700 bg-white border border-gray-200 hover:bg-amber-50 hover:border-amber-300' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach
                                @if ($roomTypes->hasMorePages())
                                    <a href="{{ $roomTypes->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-amber-50 hover:border-amber-300 transition">Next</a>
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

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeDeleteModal()"></div>
            <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 transform transition-all">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="font-playfair text-xl font-bold text-gray-900 mb-2" id="modalTitle">Hapus Room Type?</h3>
                    <p class="text-sm text-gray-500 mb-6">Data yang dihapus tidak bisa dikembalikan.</p>
                    <form id="deleteForm" method="POST" class="flex flex-col sm:flex-row gap-3 justify-center">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold bg-red-600 hover:bg-red-700 text-white transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                        <button type="button" onclick="closeDeleteModal()"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const deleteForm = document.getElementById('deleteForm');
        const deleteModal = document.getElementById('deleteModal');
        const modalTitle = document.getElementById('modalTitle');

        function openDeleteModal(id, name) {
            deleteForm.action = '{{ url('admin/room-types') }}/' + id;
            modalTitle.textContent = 'Hapus ' + name + '?';
            deleteModal.classList.remove('hidden');
        }
        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>
@endsection
