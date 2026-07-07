@extends('layouts.manager')

@section('pageTitle', 'Laporan Keuangan')

@section('content')

<div class="py-2">

    {{-- ── Header ──────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 font-playfair">Laporan Keuangan</h2>
            <p class="text-gray-500 text-sm mt-1">{{ $filterLabel }}</p>
        </div>
        <a href="{{ route('manager.report.pdf', request()->query()) }}"
            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Download PDF
        </a>
    </div>

    {{-- ── Filter Bar ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <form method="GET" action="{{ route('manager.finance') }}" class="flex flex-wrap items-end gap-3">

            {{-- Preset filter --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Periode</label>
                <select name="filter" id="filterSelect"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-gray-50"
                    onchange="toggleCustom(this.value)">
                    @foreach(['hari' => 'Hari Ini', 'minggu' => 'Minggu Ini', 'bulan' => 'Bulan Ini', 'tahun' => 'Tahun Ini', 'custom' => 'Custom Range', 'semua' => 'Semua Waktu'] as $val => $label)
                        <option value="{{ $val }}" {{ request('filter', 'bulan') === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Custom date range --}}
            <div id="customRange" class="{{ request('filter') === 'custom' ? 'flex' : 'hidden' }} items-end gap-2">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Dari</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Sampai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-gray-50">
                </div>
            </div>

            <button type="submit"
                class="bg-sky-600 hover:bg-sky-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition">
                Filter
            </button>

            <a href="{{ route('manager.finance') }}"
                class="text-gray-400 hover:text-gray-600 text-sm transition px-2 py-2">Reset</a>
        </form>
    </div>

    {{-- ── Summary Cards ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Total Revenue</p>
            <p class="text-lg font-bold text-gray-800">{{ format_rupiah($totalRevenue) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-5">
            <p class="text-xs text-green-600 font-semibold uppercase tracking-wide mb-1">Paid</p>
            <p class="text-lg font-bold text-gray-800">{{ format_rupiah($paidTotal) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $paidCount }} transaksi</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-yellow-100 p-5">
            <p class="text-xs text-yellow-600 font-semibold uppercase tracking-wide mb-1">Pending</p>
            <p class="text-lg font-bold text-gray-800">{{ format_rupiah($pendingTotal) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $pendingCount }} transaksi</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
            <p class="text-xs text-red-600 font-semibold uppercase tracking-wide mb-1">Cancelled</p>
            <p class="text-lg font-bold text-gray-800">{{ format_rupiah($cancelledTotal) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $cancelledCount }} transaksi</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-5">
            <p class="text-xs text-purple-600 font-semibold uppercase tracking-wide mb-1">Refunded</p>
            <p class="text-lg font-bold text-gray-800">{{ format_rupiah($refundedTotal) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $refundedCount }} transaksi</p>
        </div>

    </div>

    {{-- ── Revenue Charts ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Revenue Bulanan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-800 mb-4">Revenue Bulanan {{ now()->year }}</h3>
            <canvas id="revenueMonthlyChart" height="120"></canvas>
        </div>

        {{-- Revenue Tahunan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-800 mb-4">Revenue Tahunan</h3>
            <canvas id="revenueYearlyChart" height="120"></canvas>
        </div>

    </div>

    {{-- ── Detail Transaksi ─────────────────────────────────────── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-800">Detail Transaksi</h3>
            <span class="text-xs text-gray-400">{{ $transactions->total() }} transaksi ditemukan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">#</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Booking ID</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tamu</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kamar</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Check In</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Check Out</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Metode</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($transactions as $i => $payment)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 text-gray-400">{{ $transactions->firstItem() + $i }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ optional($payment->created_at)->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-sky-600">#{{ str_pad($payment->booking_id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800">{{ $payment->booking?->guest?->user?->name ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $payment->booking?->room?->room_number ?? '-' }}
                                <span class="text-gray-400 text-xs block">{{ $payment->booking?->room?->roomType?->name ?? '' }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ optional($payment->booking?->check_in)->format('d M Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ optional($payment->booking?->check_out)->format('d M Y') ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600 capitalize">{{ $payment->payment_method ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'paid'      => 'bg-green-100 text-green-700',
                                        'pending'   => 'bg-yellow-100 text-yellow-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        'refunded'  => 'bg-purple-100 text-purple-700',
                                        'failed'    => 'bg-gray-100 text-gray-700',
                                        'expired'   => 'bg-gray-100 text-gray-700',
                                    ];
                                    $color = $statusColors[$payment->payment_status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $color }}">
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                {{ format_rupiah($payment->amount) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-10 text-center text-gray-400">
                                Tidak ada transaksi ditemukan untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Revenue Bulanan
    const monthlyData = @json($revenueMonthly);
    new Chart(document.getElementById('revenueMonthlyChart'), {
        type: 'bar',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [{
                label: 'Revenue',
                data: monthlyData.map(d => d.amount),
                backgroundColor: 'rgba(8,145,178,0.7)',
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID') } } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'jt', font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { ticks: { font: { size: 10 } }, grid: { display: false } }
            }
        }
    });

    // Revenue Tahunan
    const yearlyData = @json($revenueYearly);
    new Chart(document.getElementById('revenueYearlyChart'), {
        type: 'line',
        data: {
            labels: yearlyData.map(d => d.year),
            datasets: [{
                label: 'Revenue',
                data: yearlyData.map(d => d.amount),
                borderColor: '#059669',
                backgroundColor: 'rgba(5,150,105,0.07)',
                borderWidth: 2.5,
                pointBackgroundColor: '#059669',
                pointRadius: 5,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID') } } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'jt', font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { ticks: { font: { size: 10 } }, grid: { display: false } }
            }
        }
    });

    // Toggle custom date range
    function toggleCustom(val) {
        document.getElementById('customRange').classList.toggle('hidden', val !== 'custom');
        document.getElementById('customRange').classList.toggle('flex', val === 'custom');
    }
</script>

@endsection
