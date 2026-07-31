<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Registration;
use App\Models\Treatment;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Payment;
use App\Models\Setting;
use App\Http\Requests\MedicalRecordRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['patient', 'doctor', 'registration']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mr_number', 'like', "%{$search}%");
            })->orWhere('record_number', 'like', "%{$search}%")
              ->orWhere('diagnosis', 'like', "%{$search}%");
        }

        $records = $query->latest('examination_date')->paginate(10)->withQueryString();
        $medicalRecords = $records;

        return view('medical-records.index', compact('records', 'medicalRecords'));
    }

    public function create(Request $request)
    {
        $selectedRegistrationId = $request->get('registration_id');

        $registrations = Registration::with(['patient', 'clinic', 'doctor'])
            ->whereIn('status', ['menunggu', 'dipanggil', 'pemeriksaan'])
            ->orderBy('id', 'desc')
            ->get();

        if ($selectedRegistrationId && !$registrations->contains('id', $selectedRegistrationId)) {
            $selectedReg = Registration::with(['patient', 'clinic', 'doctor'])->find($selectedRegistrationId);
            if ($selectedReg) {
                $registrations->prepend($selectedReg);
            }
        }

        $registration = $selectedRegistrationId ? Registration::with(['patient', 'doctor', 'clinic'])->find($selectedRegistrationId) : null;
        $treatments = Treatment::where('is_active', true)->get();
        $medicines = Medicine::where('stock', '>', 0)->get();

        return view('medical-records.create', compact('registrations', 'selectedRegistrationId', 'registration', 'treatments', 'medicines'));
    }

    public function store(MedicalRecordRequest $request)
    {
        $validated = $request->validated();
        $registration = Registration::findOrFail($validated['registration_id']);

        // Calculate BMI
        $bmi = null;
        if (!empty($validated['height']) && !empty($validated['weight'])) {
            $heightInMeters = $validated['height'] / 100;
            if ($heightInMeters > 0) {
                $bmi = round($validated['weight'] / ($heightInMeters * $heightInMeters), 1);
            }
        }

        // Auto Record Number
        $prefix = 'RMREC-' . Carbon::now()->format('Ymd') . '-';
        $countToday = MedicalRecord::whereDate('created_at', Carbon::today())->count();
        $recordNum = $prefix . str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);

        $medicalRecord = MedicalRecord::create([
            'record_number' => $recordNum,
            'registration_id' => $registration->id,
            'patient_id' => $registration->patient_id,
            'doctor_id' => $registration->doctor_id,
            'examination_date' => Carbon::now(),
            'complaints' => $validated['complaints'],
            'medical_history' => $validated['medical_history'] ?? null,
            'blood_pressure' => $validated['blood_pressure'] ?? null,
            'temperature' => $validated['temperature'] ?? null,
            'height' => $validated['height'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'bmi' => $bmi,
            'diagnosis' => $validated['diagnosis'],
            'doctor_notes' => $validated['doctor_notes'] ?? null,
        ]);

        // Attach Treatments
        $treatmentFee = 0;
        $treatmentList = $validated['treatment_ids'] ?? $validated['treatments'] ?? [];
        if (!empty($treatmentList)) {
            foreach ($treatmentList as $tId) {
                $treatment = Treatment::find($tId);
                if ($treatment) {
                    $medicalRecord->treatments()->attach($tId, [
                        'price' => $treatment->price,
                        'notes' => 'Tindakan saat pemeriksaan'
                    ]);
                    $treatmentFee += $treatment->price;
                }
            }
        }

        // Auto Prescription if medicines are provided
        $medicineFee = 0;
        if (!empty($request->medicines) && is_array($request->medicines)) {
            $prefixRsp = 'RSP-' . Carbon::now()->format('Ymd') . '-';
            $countRsp = Prescription::whereDate('created_at', Carbon::today())->count();
            $rspNum = $prefixRsp . str_pad($countRsp + 1, 3, '0', STR_PAD_LEFT);

            $prescription = Prescription::create([
                'prescription_number' => $rspNum,
                'medical_record_id' => $medicalRecord->id,
                'patient_id' => $registration->patient_id,
                'doctor_id' => $registration->doctor_id,
                'status' => 'menunggu',
                'total_amount' => 0,
                'notes' => 'Resep dari hasil rekam medis ' . $recordNum,
            ]);

            foreach ($request->medicines as $mItem) {
                if (empty($mItem['id'])) continue;
                $med = Medicine::find($mItem['id']);
                if ($med) {
                    $qty = isset($mItem['quantity']) && (int)$mItem['quantity'] > 0 ? (int)$mItem['quantity'] : 1;
                    $sub = $med->selling_price * $qty;
                    $medicineFee += $sub;

                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_id' => $med->id,
                        'quantity' => $qty,
                        'dosage' => '1 Tablet',
                        'instruction' => $mItem['instruction'] ?? '3 x 1 Sesudah makan',
                        'price' => $med->selling_price,
                        'subtotal' => $sub,
                    ]);
                }
            }

            $prescription->update(['total_amount' => $medicineFee]);
        }

        // Update Registration Status
        $registration->update(['status' => 'selesai']);

        // Auto Create Payment Pending Record
        $consultationFee = $registration->doctor ? $registration->doctor->consultation_fee : 50000;
        $registrationFee = (float) Setting::get('registration_fee', 15000);
        $totalInitial = $consultationFee + $treatmentFee + $medicineFee + $registrationFee;

        Payment::create([
            'payment_number' => 'BYR-' . Carbon::now()->format('Ymd') . '-' . str_pad(Payment::count() + 1, 3, '0', STR_PAD_LEFT),
            'registration_id' => $registration->id,
            'patient_id' => $registration->patient_id,
            'consultation_fee' => $consultationFee,
            'treatment_fee' => $treatmentFee,
            'medicine_fee' => $medicineFee,
            'discount' => 0,
            'tax' => 0,
            'total_amount' => $totalInitial,
            'status' => 'pending',
        ]);

        return redirect()->route('medical-records.show', $medicalRecord)
            ->with('success', 'Pemeriksaan medis berhasil disimpan.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['patient', 'doctor.clinic', 'registration', 'treatments', 'prescriptions.items.medicine']);
        return view('medical-records.show', compact('medicalRecord'));
    }

    public function downloadPdf(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['patient', 'doctor.clinic', 'registration', 'treatments', 'prescriptions.items.medicine']);
        $pdf = Pdf::loadView('medical-records.pdf', compact('medicalRecord'));
        return $pdf->download('Rekam_Medis_' . $medicalRecord->patient->mr_number . '.pdf');
    }
}
