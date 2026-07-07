<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            color: #111827;
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 5px 4px;
            vertical-align: top;
            font-size: 8px;
        }

        th {
            background: #f3f4f6;
            text-align: left;
            font-weight: bold;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #0f766e;
            margin: 14px 0 6px;
            padding-bottom: 2px;
            border-bottom: 1px solid #cbd5e1;
        }

        .box {
            border: 1px solid #d1d5db;
            padding: 10px;
            margin-bottom: 10px;
        }

        .small {
            font-size: 8px;
            color: #6b7280;
        }

        .total {
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="box">
        <table style="margin-bottom: 0;">
            <tr>
                <td style="border: none; padding: 0;">
                    <h2 style="font-size: 16px; color: #0f766e; margin: 0;">{{ $hotelProfile?->name ?? config('app.name') }}</h2>
                    <p class="small">{{ $hotelProfile?->address ?? '-' }}</p>
                    <p class="small">Telp: {{ $hotelProfile?->phone ?? '-' }} | Email: {{ $hotelProfile?->email ?? '-' }}</p>
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    <p style="font-size: 12px; font-weight: bold;">LAPORAN KEUANGAN</p>
                    <p class="small">Periode: {{ $filterLabel }}</p>
                    <p class="small">Tanggal Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
                    <p class="small">Dibuat oleh: {{ $manager->name }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Ringkasan</div>
    <table>
        <tr>
            <th style="width: 33%;">Total Revenue</th>
            <th style="width: 33%;">Paid</th>
            <th style="width: 34%;">Pending</th>
        </tr>
        <tr>
            <td class="total">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            <td class="total">Rp {{ number_format($paidTotal, 0, ',', '.') }}</td>
            <td class="total">Rp {{ number_format($pendingTotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Cancelled</th>
            <th>Refunded</th>
            <th>Total Booking</th>
        </tr>
        <tr>
            <td class="total">Rp {{ number_format($cancelledTotal, 0, ',', '.') }}</td>
            <td class="total">Rp {{ number_format($refundedTotal, 0, ',', '.') }}</td>
            <td class="total">
                {{ number_format($totalBookings) }}
                <div class="small">Occupancy: {{ $occupancyRate }}%</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Detail Transaksi</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Booking ID</th>
                <th>Nama Tamu</th>
                <th>No Kamar</th>
                <th>Tipe</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Malam</th>
                <th>Metode</th>
                <th>St. Booking</th>
                <th>St. Bayar</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ optional($p->created_at)->format('d/m/Y') }}</td>
                    <td>#{{ str_pad($p->booking_id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $p->booking?->guest?->user?->name ?? '-' }}</td>
                    <td>{{ $p->booking?->room?->room_number ?? '-' }}</td>
                    <td>{{ $p->booking?->room?->roomType?->name ?? '-' }}</td>
                    <td>{{ optional($p->booking?->check_in)->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ optional($p->booking?->check_out)->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $p->booking?->total_nights ?? '-' }}</td>
                    <td>{{ $p->payment_method ?? '-' }}</td>
                    <td>{{ $p->booking?->status ?? '-' }}</td>
                    <td>{{ ucfirst($p->payment_status ?? '-') }}</td>
                    <td>Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                    <td>—</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $revenuePerMonthCount = is_countable($revenuePerMonth) ? count($revenuePerMonth) : 0;
        $revenuePerRoomTypeCount = $revenuePerRoomType instanceof \Illuminate\Support\Collection
            ? $revenuePerRoomType->count()
            : (is_countable($revenuePerRoomType) ? count($revenuePerRoomType) : 0);
        $revenuePerPaymentMethodCount = $revenuePerPaymentMethod instanceof \Illuminate\Support\Collection
            ? $revenuePerPaymentMethod->count()
            : (is_countable($revenuePerPaymentMethod) ? count($revenuePerPaymentMethod) : 0);
    @endphp

    <div class="section-title">Revenue per Bulan</div>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @if ($revenuePerMonthCount > 0)
                @foreach ($revenuePerMonth as $month => $amount)
                    <tr>
                        <td>{{ $month }}</td>
                        <td>Rp {{ number_format($amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>—</td>
                    <td>—</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">Revenue per Room Type</div>
    <table>
        <thead>
            <tr>
                <th>Room Type</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @if ($revenuePerRoomTypeCount > 0)
                @foreach ($revenuePerRoomType as $type => $amount)
                    <tr>
                        <td>{{ $type }}</td>
                        <td>Rp {{ number_format($amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>—</td>
                    <td>—</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">Revenue per Metode Bayar</div>
    <table>
        <thead>
            <tr>
                <th>Metode</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @if ($revenuePerPaymentMethodCount > 0)
                @foreach ($revenuePerPaymentMethod as $method => $amount)
                    <tr>
                        <td>{{ $method ?? 'Lainnya' }}</td>
                        <td>Rp {{ number_format($amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>—</td>
                    <td>—</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="box" style="margin-top: 12px;">
        <table style="margin-bottom: 0;">
            <tr>
                <td style="border: none; padding: 0;">Grand Total Revenue</td>
                <td style="border: none; padding: 0; text-align: right; font-weight: bold;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
