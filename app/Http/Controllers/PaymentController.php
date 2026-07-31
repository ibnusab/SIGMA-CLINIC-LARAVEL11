<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Registration;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['patient', 'registration.doctor']);

        if ($request->filled('status')) {
            if ($request->status === 'unpaid') {
                $query->whereIn('status', ['unpaid', 'pending', 'belum_bayar']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mr_number', 'like', "%{$search}%");
            })->orWhere('payment_number', 'like', "%{$search}%");
        }

        $payments = $query->latest()->paginate(10)->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['patient', 'registration.doctor.clinic', 'registration.medicalRecord.treatments', 'registration.medicalRecord.prescriptions.items.medicine', 'invoice']);
        return view('payments.show', compact('payment'));
    }

    public function pay(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'paid_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $discount = $validated['discount'] ?? $payment->discount ?? 0;
        $consultation = $payment->consultation_fee ?? 0;
        $treatment = $payment->treatment_fee ?? 0;
        $medicine = $payment->medicine_fee ?? 0;

        $total = ($consultation + $treatment + $medicine) - $discount;
        if ($total < 0) $total = 0;

        $paymentMethod = strtolower($validated['payment_method']);
        if (in_array($paymentMethod, ['cash', 'tunai'])) {
            $paymentMethod = 'tunai';
        }

        $payment->update([
            'discount' => $discount,
            'payment_method' => $paymentMethod,
            'total_amount' => $total,
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        // Reduce Medicine Stock & Record Stock Movement
        if ($payment->registration && $payment->registration->medicalRecord) {
            $prescriptions = $payment->registration->medicalRecord->prescriptions;
            foreach ($prescriptions as $prescription) {
                $prescription->update(['status' => 'selesai']);
                foreach ($prescription->items as $item) {
                    $alreadyDeducted = \App\Models\StockMovement::where('medicine_id', $item->medicine_id)
                        ->where('reference', $prescription->prescription_number)
                        ->where('type', 'out')
                        ->exists();

                    if (!$alreadyDeducted) {
                        $med = \App\Models\Medicine::find($item->medicine_id);
                        if ($med) {
                            $med->decrement('stock', $item->quantity);
                            \App\Models\StockMovement::create([
                                'medicine_id' => $med->id,
                                'type' => 'out',
                                'quantity' => $item->quantity,
                                'reference' => $prescription->prescription_number,
                                'notes' => 'Pembayaran Kasir - Pasien: ' . ($payment->patient->name ?? '-'),
                                'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                            ]);
                        }
                    }
                }
            }
        }

        // Auto Generate Invoice when Paid
        if (!$payment->invoice) {
            Invoice::create([
                'invoice_number' => 'INV-' . Carbon::now()->format('Ymd') . '-' . str_pad(Invoice::count() + 1, 3, '0', STR_PAD_LEFT),
                'payment_id' => $payment->id,
                'issued_at' => Carbon::now(),
                'total' => $total,
                'notes' => 'Pembayaran lunas via ' . strtoupper($paymentMethod),
            ]);
        }

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dikonfirmasi LUNAS, stok obat terpotong otomatis, dan dicatat ke Laporan Kasir.');
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $discount = $validated['discount'] ?? $payment->discount ?? 0;
        $consultation = $payment->consultation_fee ?? 0;
        $treatment = $payment->treatment_fee ?? 0;
        $medicine = $payment->medicine_fee ?? 0;

        $total = ($consultation + $treatment + $medicine) - $discount;
        if ($total < 0) $total = 0;

        $status = strtolower($validated['status']);
        $paymentMethod = $validated['payment_method'] ?? $payment->payment_method ?? 'tunai';

        $payment->update([
            'discount' => $discount,
            'payment_method' => $paymentMethod,
            'total_amount' => $total,
            'status' => $status,
            'paid_at' => ($status === 'paid' || $status === 'lunas') ? ($payment->paid_at ?? Carbon::now()) : null,
        ]);

        if ($status === 'paid' || $status === 'lunas') {
            if ($payment->registration && $payment->registration->medicalRecord) {
                $prescriptions = $payment->registration->medicalRecord->prescriptions;
                foreach ($prescriptions as $prescription) {
                    $prescription->update(['status' => 'selesai']);
                    foreach ($prescription->items as $item) {
                        $alreadyDeducted = \App\Models\StockMovement::where('medicine_id', $item->medicine_id)
                            ->where('reference', $prescription->prescription_number)
                            ->where('type', 'out')
                            ->exists();

                        if (!$alreadyDeducted) {
                            $med = \App\Models\Medicine::find($item->medicine_id);
                            if ($med) {
                                $med->decrement('stock', $item->quantity);
                                \App\Models\StockMovement::create([
                                    'medicine_id' => $med->id,
                                    'type' => 'out',
                                    'quantity' => $item->quantity,
                                    'reference' => $prescription->prescription_number,
                                    'notes' => 'Pembayaran Kasir - Pasien: ' . ($payment->patient->name ?? '-'),
                                    'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                                ]);
                            }
                        }
                    }
                }
            }

            if (!$payment->invoice) {
                Invoice::create([
                    'invoice_number' => 'INV-' . Carbon::now()->format('Ymd') . '-' . str_pad(Invoice::count() + 1, 3, '0', STR_PAD_LEFT),
                    'payment_id' => $payment->id,
                    'issued_at' => Carbon::now(),
                    'total' => $total,
                    'notes' => 'Pembayaran lunas via ' . strtoupper($paymentMethod),
                ]);
            }
        }

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Status Pembayaran berhasil diperbarui.');
    }

    public function printInvoice(Payment $payment, Request $request)
    {
        $payment->load(['patient', 'registration.doctor.clinic', 'registration.medicalRecord.treatments', 'registration.medicalRecord.prescriptions.items.medicine', 'invoice']);
        
        if ($request->has('pdf')) {
            $pdf = Pdf::loadView('payments.invoice', compact('payment'));
            return $pdf->download('Kwitansi_' . $payment->payment_number . '.pdf');
        }

        return view('payments.invoice', compact('payment'));
    }
}
