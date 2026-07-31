<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Medicine;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Key Performance Indicators (KPIs)
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::where('is_active', true)->count();
        $totalClinics = Clinic::where('is_active', true)->count();
        $todayVisits = Registration::whereDate('registration_date', $today)->count();
        $todayRevenue = Payment::where(function($q) use ($today) {
            $q->whereDate('paid_at', $today)
              ->orWhere(function($sub) use ($today) {
                  $sub->whereNull('paid_at')->whereDate('created_at', $today);
              });
        })->whereIn('status', ['paid', 'lunas'])->sum('total_amount');

        // Low stock warning
        $lowStockMedicines = Medicine::whereRaw('stock <= min_stock')->get();

        // Active Queue Today
        $todayQueue = Registration::with(['patient', 'doctor', 'clinic'])
            ->whereDate('registration_date', $today)
            ->orderBy('queue_number', 'asc')
            ->limit(10)
            ->get();

        // Recent Registered Patients
        $recentPatients = Patient::latest()->limit(5)->get();

        // Today's Doctor Schedules & Quota Status
        $dayNameIndo = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ][Carbon::now()->format('l')] ?? 'Senin';

        $todaySchedules = Schedule::with(['doctor.clinic', 'doctor.user'])
            ->where('day', $dayNameIndo)
            ->where('is_active', true)
            ->get();

        foreach ($todaySchedules as $sch) {
            $usedCount = Registration::where('doctor_id', $sch->doctor_id)
                ->whereDate('registration_date', $today)
                ->where('status', '!=', 'batal')
                ->count();
            $sch->used_quota = $usedCount;
            $sch->remaining_quota = max(0, $sch->quota - $usedCount);
        }

        // Monthly Visits Graph Data (Last 6 Months)
        $monthlyVisitsData = [];
        $monthlyRevenueData = [];
        $monthLabels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabels[] = $month->translatedFormat('F Y');
            
            $visits = Registration::whereYear('registration_date', $month->year)
                ->whereMonth('registration_date', $month->month)
                ->count();
            $monthlyVisitsData[] = $visits;

            $rev = Payment::whereYear('paid_at', $month->year)
                ->whereMonth('paid_at', $month->month)
                ->where('status', 'paid')
                ->sum('total_amount');
            $monthlyRevenueData[] = (float) $rev;
        }

        return view('dashboard.index', compact(
            'totalPatients',
            'totalDoctors',
            'totalClinics',
            'todayVisits',
            'todayRevenue',
            'lowStockMedicines',
            'todayQueue',
            'recentPatients',
            'todaySchedules',
            'monthLabels',
            'monthlyVisitsData',
            'monthlyRevenueData'
        ));
    }
}
