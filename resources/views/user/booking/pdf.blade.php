<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservation Voucher - {{ $booking->invoice_number }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #d97706;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header .logo {
            width: 60px;
            height: 60px;
            background-color: #d97706;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            color: #1a1208;
            margin: 5px 0;
        }
        .header .subtitle {
            font-size: 14px;
            color: #d97706;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .invoice-info .left {
            flex: 1;
        }
        .invoice-info .right {
            text-align: right;
        }
        .invoice-info .label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
        }
        .invoice-info .value {
            font-weight: bold;
            font-size: 14px;
            color: #1a1208;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1a1208;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 20px;
        }
        .grid-item .label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
        }
        .grid-item .value {
            font-weight: 600;
            font-size: 12px;
            color: #333;
        }
        .qrcode-section {
            text-align: center;
            margin: 20px 0;
        }
        .qrcode-section img {
            width: 120px;
            height: 120px;
        }
        .terms {
            font-size: 9px;
            color: #888;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            margin-top: 15px;
        }
        .terms h4 {
            font-size: 10px;
            color: #555;
            margin: 0 0 5px 0;
        }
        .terms ul {
            margin: 0;
            padding-left: 15px;
        }
        .terms ul li {
            margin-bottom: 2px;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            color: #aaa;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-confirmed {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .status-paid {
            background: #d1fae5;
            color: #059669;
        }
        table.payment-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.payment-table td {
            padding: 4px 8px;
        }
        table.payment-table td.label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            width: 40%;
        }
        table.payment-table td.value {
            font-weight: 600;
            font-size: 12px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="logo">{{ substr(hotel_name(), 0, 1) }}</div>
        <h1>{{ hotel_name() }}</h1>
        <div class="subtitle">Reservation Voucher</div>
    </div>

    {{-- Invoice Info --}}
    <div class="invoice-info">
        <div class="left">
            <div class="label">Invoice Number</div>
            <div class="value">{{ $booking->invoice_number }}</div>
            <div class="label" style="margin-top:5px;">Booking Date</div>
            <div class="value" style="font-size:12px;">{{ $booking->created_at->format('d M Y H:i') }}</div>
        </div>
        <div class="right">
            <span class="status-badge status-confirmed">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
            @if ($booking->payment)
                <span class="status-badge status-paid" style="margin-left:5px;">{{ strtoupper($booking->payment->payment_status) }}</span>
            @endif
        </div>
    </div>

    {{-- Guest Information --}}
    <div class="section">
        <div class="section-title">Guest Information</div>
        <div class="grid-2">
            <div class="grid-item">
                <div class="label">Full Name</div>
                <div class="value">{{ $booking->guest->full_name ?? $booking->guest->user->name ?? '-' }}</div>
            </div>
            <div class="grid-item">
                <div class="label">Email</div>
                <div class="value">{{ $booking->guest->user->email ?? '-' }}</div>
            </div>
            <div class="grid-item">
                <div class="label">Phone Number</div>
                <div class="value">{{ $booking->guest->phone ?? '-' }}</div>
            </div>
            <div class="grid-item">
                <div class="label">Address</div>
                <div class="value">{{ $booking->guest->address ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Room Information --}}
    <div class="section">
        <div class="section-title">Room Information</div>
        <div class="grid-2">
            <div class="grid-item">
                <div class="label">Room Type</div>
                <div class="value">{{ $booking->room->roomType->name ?? '-' }}</div>
            </div>
            <div class="grid-item">
                <div class="label">Room Number</div>
                <div class="value">{{ $booking->room->room_number ?? '-' }}</div>
            </div>
            <div class="grid-item">
                <div class="label">Check-in Date</div>
                <div class="value">{{ $booking->check_in->format('d M Y') }}</div>
            </div>
            <div class="grid-item">
                <div class="label">Check-out Date</div>
                <div class="value">{{ $booking->check_out->format('d M Y') }}</div>
            </div>
            <div class="grid-item">
                <div class="label">Number of Nights</div>
                <div class="value">{{ $booking->total_nights }} malam</div>
            </div>
        </div>
    </div>

    {{-- Payment Information --}}
    @if ($booking->payment)
    <div class="section">
        <div class="section-title">Payment Information</div>
        <table class="payment-table">
            <tr>
                <td class="label">Room Price</td>
                <td class="value">{{ format_rupiah($booking->total_price) }}</td>
            </tr>
            <tr>
                <td class="label">Total Payment</td>
                <td class="value" style="font-size:14px;">{{ format_rupiah($booking->payment->amount ?? $booking->total_price) }}</td>
            </tr>
            <tr>
                <td class="label">Payment Method</td>
                <td class="value">{{ $booking->payment->payment_method ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Payment Date</td>
                <td class="value">{{ $booking->payment->paid_at ? $booking->payment->paid_at->format('d M Y H:i') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Payment Status</td>
                <td class="value">{{ ucfirst($booking->payment->payment_status) }}</td>
            </tr>
        </table>
    </div>
    @endif

    {{-- QR Code --}}
    <div class="qrcode-section">
        <div style="font-size:12px; font-weight:bold; margin-bottom:10px;">Booking QR Code</div>
        {!! QrCode::size(120)->generate($booking->id) !!}
        <div style="font-size:9px; color:#888; margin-top:5px;">Booking ID: #{{ $booking->id }}</div>
    </div>

    {{-- Terms & Conditions --}}
    <div class="terms">
        <h4>Terms & Conditions</h4>
        <ul>
            <li>Voucher ini berlaku sesuai dengan tanggal check-in dan check-out yang tertera.</li>
            <li>Harap tunjukkan voucher ini (cetak atau digital) kepada resepsionis saat check-in.</li>
            <li>Check-in dimulai pukul 14:00 WIB dan check-out paling lambat pukul 12:00 WIB.</li>
            <li>Identitas diri (KTP) yang valid diperlukan saat check-in.</li>
            <li>Pembatalan tidak dapat dilakukan setelah masa check-in dimulai.</li>
            <li>Hotel berhak menolak check-in jika data tidak sesuai dengan pemesanan.</li>
        </ul>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>{{ hotel_name() }} &mdash; Reservation Voucher &mdash; {{ date('Y') }}</p>
        <p>Voucher ini adalah bukti pemesanan yang sah.</p>
    </div>
</body>
</html>
