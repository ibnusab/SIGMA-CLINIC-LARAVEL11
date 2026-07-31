<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes - SIGMA CLINIC (Laravel 11)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard (All Roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin, Resepsionis, Dokter: Pasien
    Route::middleware(['role:admin,resepsionis,dokter'])->group(function () {
        Route::resource('patients', PatientController::class);
        Route::get('/patients/{patient}/card', [PatientController::class, 'printCard'])->name('patients.card');
    });

    // Admin: User Management, Dokter, Poli, Schedule, Supplier, Settings
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('doctors', DoctorController::class);
        Route::resource('clinics', ClinicController::class);
        Route::resource('schedules', ScheduleController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::put('/settings', [SettingController::class, 'update']);
    });

    // Resepsionis & Admin: Pendaftaran Kunjungan
    Route::middleware(['role:admin,resepsionis'])->group(function () {
        Route::resource('registrations', RegistrationController::class);
        Route::patch('/registrations/{registration}/update-status', [RegistrationController::class, 'update'])->name('registrations.update-status');
        Route::get('/registrations/{registration}/ticket', [RegistrationController::class, 'printTicket'])->name('registrations.ticket');
    });

    // Dokter & Admin: Rekam Medis & Pemeriksaan
    Route::middleware(['role:admin,dokter'])->group(function () {
        Route::resource('medical-records', MedicalRecordController::class);
        Route::get('/medical-records/{medical_record}/pdf', [MedicalRecordController::class, 'downloadPdf'])->name('medical-records.pdf');
    });

    // Apoteker & Admin & Dokter: Obat & Resep
    Route::middleware(['role:admin,apoteker,dokter'])->group(function () {
        Route::resource('medicines', MedicineController::class);
        Route::resource('prescriptions', PrescriptionController::class);
        Route::post('/prescriptions/{prescription}/process', [PrescriptionController::class, 'process'])->name('prescriptions.process');
        Route::patch('/prescriptions/{prescription}/update-status', [PrescriptionController::class, 'process'])->name('prescriptions.update-status');
    });

    // Admin, Resepsionis & Dokter: Tindakan
    Route::middleware(['role:admin,resepsionis,dokter'])->group(function () {
        Route::resource('treatments', TreatmentController::class);
    });

    // Admin & Resepsionis: Pembayaran
    Route::middleware(['role:admin,resepsionis'])->group(function () {
        Route::resource('payments', PaymentController::class);
        Route::post('/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
        Route::get('/payments/{payment}/invoice', [PaymentController::class, 'printInvoice'])->name('payments.invoice');
    });

    // Admin: Laporan
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/kunjungan', [ReportController::class, 'kunjungan'])->name('reports.kunjungan');
        Route::get('/reports/pendapatan', [ReportController::class, 'pendapatan'])->name('reports.pendapatan');
        Route::get('/reports/obat', [ReportController::class, 'obat'])->name('reports.obat');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    });
});
