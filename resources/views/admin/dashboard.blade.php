@extends('layouts.admin')

@section('pageTitle')
    Dashboard Admin
@endsection

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert --}}
            @if (session('status'))
                <div class="mb-6 rounded-lg bg-green-100 border border-green-200 px-5 py-4 text-green-700">
                    {{ session('status') }}
                </div>
            @endif


            {{-- Metric Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">


                {{-- Total Rooms --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">

                    <div class="text-3xl mr-4">
                        🏨
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 font-semibold uppercase">
                            Total Kamar
                        </p>

                        <p class="text-2xl font-bold text-gray-800">
                            {{ $totalRooms ?? 0 }}
                        </p>

                        <p class="text-xs text-green-600 mt-1 font-semibold">
                            {{ $availableRooms ?? 0 }} Tersedia
                        </p>
                    </div>

                </div>



                {{-- Booking --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">

                    <div class="text-3xl mr-4">
                        📅
                    </div>

                    <div>

                        <p class="text-sm text-gray-500 font-semibold uppercase">
                            Booking Aktif
                        </p>

                        <p class="text-2xl font-bold text-gray-800">
                            {{ $activeBookings ?? 0 }}
                        </p>

                        <p class="text-xs text-blue-600 mt-1 font-semibold">
                            {{ $occupiedRooms ?? 0 }} Kamar Terisi
                        </p>

                    </div>

                </div>




                {{-- Guests --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">

                    <div class="text-3xl mr-4">
                        👤
                    </div>

                    <div>

                        <p class="text-sm text-gray-500 font-semibold uppercase">
                            Total Tamu
                        </p>


                        <p class="text-2xl font-bold text-gray-800">
                            {{ $totalGuests ?? 0 }}
                        </p>


                        <p class="text-xs text-gray-500 mt-1">
                            Tamu Terdaftar
                        </p>

                    </div>

                </div>




                {{-- Revenue --}}
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">


                    <div class="text-3xl mr-4">
                        💰
                    </div>


                    <div>

                        <p class="text-sm text-gray-500 font-semibold uppercase">
                            Pendapatan
                        </p>


                        <p class="text-2xl font-bold text-gray-800">

                            @if (function_exists('format_rupiah'))
                                {{ format_rupiah($totalRevenue ?? 0) }}
                            @else
                                Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                            @endif

                        </p>


                        <p class="text-xs text-gray-500 mt-1">
                            Pembayaran Lunas
                        </p>


                    </div>


                </div>


            </div>




            {{-- Main Content --}}

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">


                {{-- Latest Booking --}}

                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-100 lg:col-span-2">


                    <div class="flex justify-between items-center mb-6">

                        <h3 class="text-lg font-bold text-gray-800">
                            Booking Terbaru
                        </h3>


                        <a href="{{ route('admin.bookings.index') }}"
                            class="text-amber-600 hover:text-amber-700 font-semibold text-sm">

                            Lihat Semua →

                        </a>

                    </div>



                    @if (isset($latestBookings) && $latestBookings->count())
                        <div class="overflow-x-auto">

                            <table class="min-w-full">


                                <thead>

                                    <tr class="border-b">

                                        <th class="text-left py-3 text-sm text-gray-500">
                                            Tamu
                                        </th>

                                        <th class="text-left py-3 text-sm text-gray-500">
                                            Kamar
                                        </th>

                                        <th class="text-left py-3 text-sm text-gray-500">
                                            Tanggal
                                        </th>

                                        <th class="text-left py-3 text-sm text-gray-500">
                                            Status
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>


                                    @foreach ($latestBookings as $booking)
                                        <tr class="border-b">


                                            <td class="py-4">


                                                <p class="font-semibold text-gray-800">

                                                    {{ $booking->guest->user->name ?? '-' }}

                                                </p>


                                                <p class="text-xs text-gray-500">

                                                    {{ $booking->guest->phone ?? '-' }}

                                                </p>


                                            </td>



                                            <td class="py-4">


                                                {{ $booking->room->roomType->name ?? '-' }}


                                            </td>



                                            <td class="py-4 text-sm text-gray-600">


                                                {{ optional($booking->check_in)->format('d M Y') }}

                                                -

                                                {{ optional($booking->check_out)->format('d M Y') }}


                                            </td>



                                            <td class="py-4">


                                                <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">

                                                    {{ ucfirst($booking->status ?? 'pending') }}

                                                </span>


                                            </td>



                                        </tr>
                                    @endforeach


                                </tbody>


                            </table>


                        </div>
                    @else
                        <p class="text-gray-500 text-sm text-center py-5">

                            Belum ada booking terbaru.

                        </p>
                    @endif


                </div>





                {{-- Room Status --}}

                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-100">


                    <h3 class="text-lg font-bold text-gray-800 mb-6">

                        Status Kamar

                    </h3>



                    <div class="space-y-5">


                        <div class="flex justify-between">

                            <span class="text-gray-600">

                                🟢 Tersedia

                            </span>


                            <b>

                                {{ $availableRooms ?? 0 }}

                            </b>


                        </div>



                        <div class="flex justify-between">


                            <span class="text-gray-600">

                                🔵 Terisi

                            </span>


                            <b>

                                {{ $occupiedRooms ?? 0 }}

                            </b>


                        </div>



                        <div class="flex justify-between">


                            <span class="text-gray-600">

                                🔴 Maintenance

                            </span>


                            <b>

                                {{ $maintenanceRooms ?? 0 }}

                            </b>


                        </div>


                    </div>


                </div>


            </div>



        </div>

    </div>


@endsection 
