<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Payment;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Prescription::with(['medicalRecord', 'patient', 'doctor', 'items.medicine']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prescriptions = $query->latest()->paginate(10)->withQueryString();

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create(Request $request)
    {
        $medicalRecord = null;
        if ($request->filled('medical_record_id')) {
            $medicalRecord = MedicalRecord::with(['patient', 'doctor'])->find($request->medical_record_id);
        }

        $patients = \App\Models\Patient::where('is_active', true)->orderBy('name')->get();
        if ($patients->isEmpty()) {
            $patients = \App\Models\Patient::orderBy('name')->get();
        }

        $doctors = \App\Models\Doctor::where('is_active', true)->orderBy('name')->get();
        if ($doctors->isEmpty()) {
            $doctors = \App\Models\Doctor::orderBy('name')->get();
        }

        $medicines = Medicine::where('stock', '>', 0)->orderBy('name')->get();
        if ($medicines->isEmpty()) {
            $medicines = Medicine::orderBy('name')->get();
        }

        return view('prescriptions.create', compact('medicalRecord', 'patients', 'doctors', 'medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'patient_id' => 'required_without:medical_record_id|nullable|exists:patients,id',
            'doctor_id' => 'required_without:medical_record_id|nullable|exists:doctors,id',
            'medicines' => 'required|array|min:1',
            'medicines.*.id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.instruction' => 'nullable|string',
        ]);

        $medicalRecordId = $request->medical_record_id;
        $patientId = $request->patient_id;
        $doctorId = $request->doctor_id;

        if ($medicalRecordId) {
            $mr = MedicalRecord::find($medicalRecordId);
            if ($mr) {
                $patientId = $mr->patient_id;
                $doctorId = $mr->doctor_id;
            }
        }

        DB::beginTransaction();
        try {
            $prefix = 'RSP-' . Carbon::now()->format('Ymd') . '-';
            $count = Prescription::whereDate('created_at', Carbon::today())->count();
            $rspNum = $prefix . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            $prescription = Prescription::create([
                'prescription_number' => $rspNum,
                'medical_record_id' => $medicalRecordId,
                'patient_id' => $patientId,
                'doctor_id' => $doctorId,
                'status' => 'menunggu',
                'total_amount' => 0,
                'notes' => $request->notes,
            ]);

            $totalAmount = 0;

            foreach ($request->medicines as $item) {
                if (empty($item['id'])) continue;
                $medicine = Medicine::findOrFail($item['id']);
                $subtotal = $medicine->selling_price * $item['quantity'];
                $totalAmount += $subtotal;

                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $medicine->id,
                    'quantity' => $item['quantity'],
                    'dosage' => $item['dosage'] ?? '1 Tablet',
                    'instruction' => $item['instruction'] ?? '3 x 1 Sesudah makan',
                    'price' => $medicine->selling_price,
                    'subtotal' => $subtotal,
                ]);
            }

            $prescription->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('prescriptions.show', $prescription)
                ->with('success', 'Resep obat berhasil dibuat dan dikirim ke Apotek.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Gagal membuat resep: ' . $e->getMessage()]);
        }
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'medicalRecord', 'items.medicine']);
        return view('prescriptions.show', compact('prescription'));
    }

    public function process(Request $request, Prescription $prescription)
    {
        $newStatus = $request->input('status', 'selesai');
        if ($newStatus === 'processed') $newStatus = 'proses';

        DB::beginTransaction();
        try {
            if ($prescription->status !== 'selesai' && $prescription->status !== 'taken') {
                foreach ($prescription->items as $item) {
                    $alreadyDeducted = StockMovement::where('medicine_id', $item->medicine_id)
                        ->where('reference', $prescription->prescription_number)
                        ->where('type', 'out')
                        ->exists();

                    if (!$alreadyDeducted) {
                        $medicine = $item->medicine;
                        if ($medicine) {
                            $medicine->decrement('stock', $item->quantity);

                            StockMovement::create([
                                'medicine_id' => $medicine->id,
                                'type' => 'out',
                                'quantity' => $item->quantity,
                                'reference' => $prescription->prescription_number,
                                'notes' => 'Penyerahan Obat Resep Pasien: ' . ($prescription->patient->name ?? '-'),
                                'user_id' => Auth::id() ?? 1,
                            ]);
                        }
                    }
                }
            }

            $prescription->update(['status' => $newStatus]);

            $registration = $prescription->medicalRecord?->registration;
            if ($registration) {
                $payment = Payment::where('registration_id', $registration->id)->first();
                if ($payment) {
                    $payment->medicine_fee = $prescription->total_amount;
                    $payment->total_amount = $payment->consultation_fee + $payment->treatment_fee + $prescription->total_amount - $payment->discount + $payment->tax;
                    $payment->save();
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Status resep obat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
        }
    }
}
