<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Payment;
use App\Models\Medicine;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function kunjungan(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $registrations = Registration::with(['patient', 'doctor', 'clinic'])
            ->whereBetween('registration_date', [$startDate, $endDate])
            ->orderBy('registration_date', 'desc')
            ->paginate(20)->withQueryString();

        $totalVisits = Registration::whereBetween('registration_date', [$startDate, $endDate])->count();

        return view('reports.kunjungan', compact('registrations', 'startDate', 'endDate', 'totalVisits'));
    }

    public function pendapatan(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $payments = Payment::with(['patient', 'registration.doctor'])
            ->whereIn('status', ['paid', 'lunas'])
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('paid_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                  ->orWhere(function($sub) use ($startDate, $endDate) {
                      $sub->whereNull('paid_at')
                          ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                  });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)->withQueryString();

        $totalRevenue = Payment::whereIn('status', ['paid', 'lunas'])
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('paid_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                  ->orWhere(function($sub) use ($startDate, $endDate) {
                      $sub->whereNull('paid_at')
                          ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                  });
            })
            ->sum('total_amount');

        return view('reports.pendapatan', compact('payments', 'startDate', 'endDate', 'totalRevenue'));
    }

    public function obat()
    {
        $medicines = Medicine::with('supplier')->orderBy('stock', 'asc')->get();
        return view('reports.obat', compact('medicines'));
    }

    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'kunjungan');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        if ($type === 'kunjungan') {
            $data = Registration::with(['patient', 'doctor', 'clinic'])
                ->whereBetween('registration_date', [$startDate, $endDate])
                ->get();
            $title = 'Laporan Kunjungan Pasien (' . $startDate . ' s/d ' . $endDate . ')';
        } elseif ($type === 'pendapatan') {
            $data = Payment::with(['patient', 'registration.doctor'])
                ->where('status', 'paid')
                ->whereBetween('paid_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->get();
            $title = 'Laporan Pendapatan Klinik (' . $startDate . ' s/d ' . $endDate . ')';
        } else {
            $data = Medicine::with('supplier')->get();
            $title = 'Laporan Stok & Invintaris Obat Apotek';
        }

        $pdf = Pdf::loadView('reports.pdf', compact('data', 'type', 'title', 'startDate', 'endDate'));
        return $pdf->download('Laporan_' . ucfirst($type) . '_' . date('Ymd') . '.pdf');
    }
}
