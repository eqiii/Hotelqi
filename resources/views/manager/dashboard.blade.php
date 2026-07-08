@extends('layouts.manager')

@section('pageTitle', 'Dashboard Manager')

@section('content')

<div class="py-2">

    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 font-playfair">Selamat Datang, {{ Auth::user()->name }}</h2>
        <p class="text-gray-500 text-sm mt-1">Ringkasan performa hotel — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- ── 6 Metric Cards ───────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">

        {{-- Card 1: Total Revenue --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Total Revenue</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">{{ format_rupiah($totalRevenue) }}</p>
                <p class="text-xs text-emerald-600 mt-0.5 font-medium">Semua Waktu</p>
            </div>
        </div>

        {{-- Card 2: Revenue Bulan Ini --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-sky-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Revenue Bulan Ini</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">{{ format_rupiah($revenueThisMonth) }}</p>
                <p class="text-xs text-sky-600 mt-0.5 font-medium">{{ now()->translatedFormat('F Y') }}</p>
            </div>
        </div>

        {{-- Card 3: Jumlah Booking --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Jumlah Booking</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($totalBookings) }}</p>
                <p class="text-xs text-violet-600 mt-0.5 font-medium">Total Semua Booking</p>
            </div>
        </div>

        {{-- Card 4: Booking Selesai --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Booking Selesai</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($completedBookings) }}</p>
                <p class="text-xs text-teal-600 mt-0.5 font-medium">Status: Checked Out</p>
            </div>
        </div>

        {{-- Card 5: Occupancy Rate --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Occupancy Rate</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">{{ $occupancyRate }}%</p>
                <p class="text-xs text-amber-600 mt-0.5 font-medium">Kamar Aktif Terisi</p>
            </div>
        </div>

        {{-- Card 6: Average Revenue per Booking --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Avg Revenue / Booking</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">{{ format_rupiah($avgRevenuePerBooking) }}</p>
                <p class="text-xs text-rose-600 mt-0.5 font-medium">Rata-rata Transaksi</p>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Restaurant Revenue Hari Ini</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">{{ format_rupiah($restaurantRevenueToday) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Restaurant Revenue Bulan Ini</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">{{ format_rupiah($restaurantRevenueThisMonth) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Total Restaurant Orders</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">{{ number_format($restaurantTotalOrders) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Average Order Value</p>
            <p class="text-xl font-bold text-gray-800 mt-0.5">{{ format_rupiah($restaurantAverageOrderValue) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Best Selling Menu</p>
            <p class="text-xl font-bold text-gray-800 mt-0.5">{{ $bestSellingMenu?->name ?? 'Belum ada' }}</p>
        </div>
    </div>

    {{-- ── Charts ───────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Revenue per Month --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-800">Revenue per Bulan</h3>
                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">{{ now()->year }}</span>
            </div>
            <canvas id="revenueChart" height="120"></canvas>
        </div>

        {{-- Bookings per Month --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-800">Booking per Bulan</h3>
                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">{{ now()->year }}</span>
            </div>
            <canvas id="bookingChart" height="120"></canvas>
        </div>

    </div>

    {{-- ── Quick Actions ─────────────────────────────────────────────── --}}
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('manager.finance') }}"
            class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Laporan Keuangan
        </a>
        <a href="{{ route('manager.report.pdf') }}"
            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Download PDF
        </a>
    </div>

</div>

{{-- Chart.js via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const revenueData = @json($revenuePerMonth);
    const bookingData = @json($bookingsPerMonth);

    // Revenue Chart — Line
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue (Rp)',
                data: revenueData,
                borderColor: '#0891b2',
                backgroundColor: 'rgba(8,145,178,0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#0891b2',
                pointRadius: 4,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt',
                        font: { size: 10 }
                    },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    ticks: { font: { size: 10 } },
                    grid: { display: false }
                }
            }
        }
    });

    // Booking Chart — Bar
    new Chart(document.getElementById('bookingChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Booking',
                data: bookingData,
                backgroundColor: 'rgba(99,102,241,0.7)',
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { font: { size: 10 }, stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    ticks: { font: { size: 10 } },
                    grid: { display: false }
                }
            }
        }
    });
</script>

@endsection
