<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\HotelProfile;
use App\Enums\PaymentStatus;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerFinanceController extends Controller
{
    /**
     * Tampilkan halaman laporan keuangan dengan filter
     */
    public function index(Request $request)
    {
        [$startDate, $endDate, $filterLabel] = $this->resolveDateRange($request);

        // ── Summary ──────────────────────────────────────────────────
        $paymentsQuery = Payment::with('booking.room.roomType', 'booking.guest.user')
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate->startOfDay()->copy()))
            ->when($endDate,   fn($q) => $q->where('created_at', '<=', $endDate->endOfDay()->copy()));

        $totalRevenue      = (clone $paymentsQuery)->where('payment_status', PaymentStatus::PAID)->sum('amount');
        $paidTotal         = (clone $paymentsQuery)->where('payment_status', PaymentStatus::PAID)->sum('amount');
        $pendingTotal      = (clone $paymentsQuery)->where('payment_status', PaymentStatus::PENDING)->sum('amount');
        $cancelledTotal    = (clone $paymentsQuery)->where('payment_status', PaymentStatus::CANCELLED)->sum('amount');
        $refundedTotal     = (clone $paymentsQuery)->where('payment_status', PaymentStatus::REFUNDED)->sum('amount');

        $paidCount      = (clone $paymentsQuery)->where('payment_status', PaymentStatus::PAID)->count();
        $pendingCount   = (clone $paymentsQuery)->where('payment_status', PaymentStatus::PENDING)->count();
        $cancelledCount = (clone $paymentsQuery)->where('payment_status', PaymentStatus::CANCELLED)->count();
        $refundedCount  = (clone $paymentsQuery)->where('payment_status', PaymentStatus::REFUNDED)->count();

        // ── Revenue bulanan (12 bulan current year) ───────────────────
        $revenueMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueMonthly[] = [
                'month'  => Carbon::create(null, $m)->translatedFormat('M'),
                'amount' => (float) Payment::where('payment_status', PaymentStatus::PAID)
                    ->whereYear('paid_at', now()->year)
                    ->whereMonth('paid_at', $m)
                    ->sum('amount'),
            ];
        }

        // ── Revenue tahunan (5 tahun terakhir) ────────────────────────
        $revenueYearly = [];
        for ($y = now()->year - 4; $y <= now()->year; $y++) {
            $revenueYearly[] = [
                'year'   => $y,
                'amount' => (float) Payment::where('payment_status', PaymentStatus::PAID)
                    ->whereYear('paid_at', $y)
                    ->sum('amount'),
            ];
        }

        // ── Detail transaksi (paginated) ──────────────────────────────
        $transactions = (clone $paymentsQuery)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('manager.finance', compact(
            'totalRevenue',
            'paidTotal',
            'pendingTotal',
            'cancelledTotal',
            'refundedTotal',
            'paidCount',
            'pendingCount',
            'cancelledCount',
            'refundedCount',
            'revenueMonthly',
            'revenueYearly',
            'transactions',
            'startDate',
            'endDate',
            'filterLabel',
            'request'
        ));
    }

    /**
     * Download PDF laporan keuangan
     */
    public function downloadPdf(Request $request)
    {
        [$startDate, $endDate, $filterLabel] = $this->resolveDateRange($request);

        $paymentsQuery = Payment::with('booking.room.roomType', 'booking.guest.user')
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate->startOfDay()->copy()))
            ->when($endDate,   fn($q) => $q->where('created_at', '<=', $endDate->endOfDay()->copy()));

        // ── Summary ──────────────────────────────────────────────────
        $totalRevenue   = (clone $paymentsQuery)->where('payment_status', PaymentStatus::PAID)->sum('amount');
        $paidTotal      = (clone $paymentsQuery)->where('payment_status', PaymentStatus::PAID)->sum('amount');
        $pendingTotal   = (clone $paymentsQuery)->where('payment_status', PaymentStatus::PENDING)->sum('amount');
        $cancelledTotal = (clone $paymentsQuery)->where('payment_status', PaymentStatus::CANCELLED)->sum('amount');
        $refundedTotal  = (clone $paymentsQuery)->where('payment_status', PaymentStatus::REFUNDED)->sum('amount');
        $paidCount      = (clone $paymentsQuery)->where('payment_status', PaymentStatus::PAID)->count();

        // ── Total Bookings ────────────────────────────────────────────
        $totalBookings = Booking::when($startDate, fn($q) => $q->where('created_at', '>=', $startDate->startOfDay()->copy()))
            ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate->endOfDay()->copy()))
            ->count();

        // ── Occupancy Rate ────────────────────────────────────────────
        $totalRooms    = Room::count();
        $occupiedRooms = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        // ── Detail transaksi ──────────────────────────────────────────
        $transactions = (clone $paymentsQuery)->latest()->get();

        // ── Rekap per bulan ───────────────────────────────────────────
        $revenuePerMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $amt = (float) Payment::where('payment_status', PaymentStatus::PAID)
                ->whereYear('paid_at', now()->year)
                ->whereMonth('paid_at', $m)
                ->sum('amount');
            if ($amt > 0) {
                $revenuePerMonth[Carbon::create(null, $m)->translatedFormat('F')] = $amt;
            }
        }

        // ── Rekap per room type ───────────────────────────────────────
        $revenuePerRoomType = RoomType::all()->mapWithKeys(function ($rt) use ($startDate, $endDate) {
            $amount = Payment::where('payment_status', PaymentStatus::PAID)
                ->when($startDate, fn($q) => $q->where('payments.created_at', '>=', $startDate->startOfDay()->copy()))
                ->when($endDate,   fn($q) => $q->where('payments.created_at', '<=', $endDate->endOfDay()->copy()))
                ->whereHas('booking.room', fn($q) => $q->where('room_type_id', $rt->id))
                ->sum('amount');
            return [$rt->name => (float) $amount];
        })->filter(fn($v) => $v > 0);

        // ── Rekap per payment method ──────────────────────────────────
        $revenuePerPaymentMethod = Payment::where('payment_status', PaymentStatus::PAID)
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate->startOfDay()->copy()))
            ->when($endDate,   fn($q) => $q->where('created_at', '<=', $endDate->endOfDay()->copy()))
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        // ── Hotel Profile ─────────────────────────────────────────────
        $hotelProfile = HotelProfile::getProfile();
        $manager      = Auth::user();

        $pdf = Pdf::loadView('manager.report-pdf', compact(
            'hotelProfile',
            'manager',
            'totalRevenue',
            'paidTotal',
            'pendingTotal',
            'cancelledTotal',
            'refundedTotal',
            'paidCount',
            'totalBookings',
            'occupancyRate',
            'transactions',
            'revenuePerMonth',
            'revenuePerRoomType',
            'revenuePerPaymentMethod',
            'filterLabel',
            'startDate',
            'endDate'
        ))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
            ]);

        $filename = 'laporan-keuangan-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Resolve date range dari request filter
     */
    private function resolveDateRange(Request $request): array
    {
        $filter = $request->input('filter', 'bulan');

        switch ($filter) {
            case 'hari':
                $start = Carbon::today();
                $end   = Carbon::today();
                $label = 'Hari Ini (' . $start->translatedFormat('d M Y') . ')';
                break;
            case 'minggu':
                $start = Carbon::now()->startOfWeek();
                $end   = Carbon::now()->endOfWeek();
                $label = 'Minggu Ini';
                break;
            case 'tahun':
                $start = Carbon::now()->startOfYear();
                $end   = Carbon::now()->endOfYear();
                $label = 'Tahun ' . now()->year;
                break;
            case 'custom':
                $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : null;
                $end   = $request->filled('end_date')   ? Carbon::parse($request->end_date)   : null;
                $label = 'Custom: ' . ($start?->translatedFormat('d M Y') ?? '—') . ' s/d ' . ($end?->translatedFormat('d M Y') ?? '—');
                break;
            case 'semua':
                $start = null;
                $end   = null;
                $label = 'Semua Waktu';
                break;
            default: // bulan
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
                $label = 'Bulan ' . now()->translatedFormat('F Y');
        }

        return [$start, $end, $label];
    }
}
