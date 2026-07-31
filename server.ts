import express from 'express';
import fs from 'fs';
import path from 'path';

const app = express();
const PORT = 3000;

app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Mock Data Store for SIGMA CLINIC Preview
const state = {
    user: { name: 'dr. Farida Utama, M.Kes', role: 'admin', email: 'admin@sigmaclinic.id' },
    patients: [
        { id: 1, mr_number: 'RM-202607-001', name: 'Budi Santoso', nik: '3171012304850001', gender: 'L', birth_date: '1985-04-12', phone: '081234567890', address: 'Jl. Melati No. 12, Jakarta', bpjs_number: '000123456789', allergies: 'Penicillin' },
        { id: 2, mr_number: 'RM-202607-002', name: 'Siti Rahmawati', nik: '3171015508900002', gender: 'P', birth_date: '1990-08-15', phone: '081987654321', address: 'Jl. Mawar No. 45, Jakarta', bpjs_number: null, allergies: 'Tidak Ada' },
        { id: 3, mr_number: 'RM-202607-003', name: 'Ahmad Dahlan', nik: '3171011010780003', gender: 'L', birth_date: '1978-10-10', phone: '085678901234', address: 'Jl. Anggrek No. 8, Jakarta', bpjs_number: '000987654321', allergies: 'Aspirin' },
    ],
    doctors: [
        { id: 1, nip_sip: 'SIP.123/DISKES/2026', name: 'dr. Andi Wijaya, Sp.PD', phone: '081122334455', email: 'andi@sigmaclinic.id', clinic: { name: 'Poli Penyakit Dalam' } },
        { id: 2, nip_sip: 'SIP.124/DISKES/2026', name: 'dr. Maya Putri, Sp.A', phone: '081122334456', email: 'maya@sigmaclinic.id', clinic: { name: 'Poli Anak' } },
    ],
    clinics: [
        { id: 1, code: 'POLI-PD', name: 'Poli Penyakit Dalam', description: 'Pelayanan penyakit dalam adult' },
        { id: 2, code: 'POLI-ANK', name: 'Poli Anak', description: 'Pelayanan kesehatan anak' },
        { id: 3, code: 'POLI-UMM', name: 'Poli Umum', description: 'Pelayanan umum' },
    ],
    schedules: [
        { id: 1, doctor_id: 1, doctor: { name: 'dr. Andi Wijaya, Sp.PD' }, clinic: { name: 'Poli Penyakit Dalam' }, day: 'Senin', start_time: '08:00', end_time: '14:00', quota: 20 },
        { id: 2, doctor_id: 2, doctor: { name: 'dr. Maya Putri, Sp.A' }, clinic: { name: 'Poli Anak' }, day: 'Selasa', start_time: '09:00', end_time: '15:00', quota: 15 },
    ],
    registrations: [
        { id: 1, registration_number: 'REG-20260730-001', queue_number: 1, registration_date: '2026-07-30', visit_type: 'bpjs', status: 'pemeriksaan', patient: { name: 'Budi Santoso', mr_number: 'RM-202607-001' }, doctor: { name: 'dr. Andi Wijaya, Sp.PD' }, clinic: { name: 'Poli Penyakit Dalam' } },
        { id: 2, registration_number: 'REG-20260730-002', queue_number: 2, registration_date: '2026-07-30', visit_type: 'umum', status: 'menunggu', patient: { name: 'Siti Rahmawati', mr_number: 'RM-202607-002' }, doctor: { name: 'dr. Maya Putri, Sp.A' }, clinic: { name: 'Poli Anak' } },
    ],
    medical_records: [
        { id: 1, record_number: 'RMED-20260730-001', examination_date: '2026-07-30 09:30:00', blood_pressure: '120/80', temperature: '36.5', weight: '65', height: '170', bmi: '22.5', complaints: 'Demam 2 hari dan pusing hebat', physical_exam: 'Tenggorokan hiperemis (+), paru bersih', diagnosis: 'ISPA / Faringitis Akut', patient: { name: 'Budi Santoso', mr_number: 'RM-202607-001', gender: 'L', age: 41, allergies: 'Penicillin' }, doctor: { name: 'dr. Andi Wijaya, Sp.PD', nip_sip: 'SIP.123/DISKES/2026', clinic: { name: 'Poli Penyakit Dalam' } }, treatments: [{ name: 'Konsultasi Dokter', price: 50000 }], prescriptions: [] },
    ],
    medicines: [
        { id: 1, code: 'OBT-PCT-500', name: 'Paracetamol 500mg Tablet', category: 'Obat Bebas', unit: 'Tablet', purchase_price: 500, selling_price: 1500, stock: 450, min_stock: 50, supplier: { name: 'PT Kimia Farma' }, expiry_date: new Date('2028-12-31') },
        { id: 2, code: 'OBT-AMX-500', name: 'Amoxicillin 500mg Kaplet', category: 'Antibiotik', unit: 'Kaplet', purchase_price: 1200, selling_price: 3000, stock: 18, min_stock: 30, supplier: { name: 'PT Kalbe Farma' }, expiry_date: new Date('2027-06-30') },
        { id: 3, code: 'OBT-CTM-004', name: 'CTM 4mg Tablet', category: 'Obat Bebas', unit: 'Tablet', purchase_price: 200, selling_price: 800, stock: 300, min_stock: 40, supplier: { name: 'PT Kimia Farma' }, expiry_date: new Date('2029-01-15') },
    ],
    prescriptions: [
        { id: 1, prescription_number: 'RSP-20260730-001', created_at: '2026-07-30 10:00:00', status: 'processed', total_price: 22500, patient: { name: 'Budi Santoso', mr_number: 'RM-202607-001' }, doctor: { name: 'dr. Andi Wijaya, Sp.PD' }, items: [{ medicine: { name: 'Paracetamol 500mg Tablet', unit: 'Tablet' }, instruction: '3 x 1 Tablet sesudah makan', quantity: 10, unit_price: 1500, subtotal: 15000 }] },
    ],
    treatments: [
        { id: 1, code: 'TND-01', name: 'Konsultasi & Pemeriksaan Dokter', category: 'Umum', price: 50000, description: 'Pemeriksaan fisik umum oleh dokter spesialis / umum' },
        { id: 2, code: 'TND-02', name: 'Injeksi / Suntikan Vitamin', category: 'Tindakan', price: 35000, description: 'Pemberian injeksi intramuskular' },
    ],
    payments: [
        { id: 1, payment_number: 'PAY-20260730-001', created_at: '2026-07-30 10:15:00', status: 'paid', consultation_fee: 50000, treatment_fee: 35000, medicine_fee: 22500, total_amount: 107500, payment_method: 'cash', paid_at: '2026-07-30 10:20:00', patient: { name: 'Budi Santoso', mr_number: 'RM-202607-001' }, registration: { doctor: { name: 'dr. Andi Wijaya, Sp.PD' }, clinic: { name: 'Poli Penyakit Dalam' }, visit_type: 'bpjs' } },
    ],
    suppliers: [
        { id: 1, code: 'SUP-KF', name: 'PT Kimia Farma Trading', phone: '021-7890123', email: 'sales@kimiafarma.co.id', address: 'Jl. Veteran No. 9, Jakarta', medicines_count: 2 },
        { id: 2, code: 'SUP-KLB', name: 'PT Kalbe Farma Tbk', phone: '021-8901234', email: 'contact@kalbe.co.id', address: 'Jl. Letjen Suprapto, Jakarta', medicines_count: 1 },
    ],
    settings: {
        clinic_name: 'SIGMA CLINIC UTAMA',
        clinic_phone: '(021) 7890123',
        clinic_email: 'info@sigmaclinic.id',
        clinic_sip: '440/123/DISKES/2026',
        clinic_address: 'Jl. Kesehatan No. 88, Jakarta Selatan',
    }
};

// Helper to render Blade files
function renderBladeView(viewPathRelative: string, data: Record<string, any> = {}): string {
    const fullPath = path.join(process.cwd(), 'resources', 'views', viewPathRelative + '.blade.php');
    if (!fs.existsSync(fullPath)) {
        return `<div>View not found: ${viewPathRelative}</div>`;
    }

    let content = fs.readFileSync(fullPath, 'utf8');

    // Handle @extends('layouts.app')
    if (content.includes("@extends('layouts.app')")) {
        const layoutPath = path.join(process.cwd(), 'resources', 'views', 'layouts', 'app.blade.php');
        let layout = fs.readFileSync(layoutPath, 'utf8');

        // Extract sections
        const titleMatch = content.match(/@section\('title',\s*'([^']+)'\)/);
        const title = titleMatch ? titleMatch[1] : 'SIGMA CLINIC';

        let bodyContent = '';
        const contentMatch = content.match(/@section\('content'\)([\s\S]*?)@endsection/);
        if (contentMatch) {
            bodyContent = contentMatch[1];
        }

        // Replace in layout
        layout = layout.replace("@yield('title', 'Sistem Informasi Manajemen Klinik')", title);
        layout = layout.replace("@yield('content')", bodyContent);
        content = layout;
    } else if (content.includes("@extends('layouts.auth')")) {
        const layoutPath = path.join(process.cwd(), 'resources', 'views', 'layouts', 'auth.blade.php');
        let layout = fs.readFileSync(layoutPath, 'utf8');

        const titleMatch = content.match(/@section\('title',\s*'([^']+)'\)/);
        const title = titleMatch ? titleMatch[1] : 'SIGMA CLINIC';

        let bodyContent = '';
        const contentMatch = content.match(/@section\('content'\)([\s\S]*?)@endsection/);
        if (contentMatch) {
            bodyContent = contentMatch[1];
        }

        layout = layout.replace("@yield('title')", title);
        layout = layout.replace("@yield('content')", bodyContent);
        content = layout;
    }

    // Replace Auth::user() references
    content = content.replace(/{{ Auth::user\(\)->name \?\? '([^']+)' }}/g, state.user.name);
    content = content.replace(/{{ Auth::user\(\)->name }}/g, state.user.name);
    content = content.replace(/{{ Auth::user\(\)->email }}/g, state.user.email);
    content = content.replace(/{{ Auth::user\(\)->role }}/g, state.user.role);

    // Clean common Laravel directives for client rendering
    content = content.replace(/@csrf/g, '');
    content = content.replace(/@method\('[A-Z]+'\)/g, '');

    return content;
}

// Routes
app.get('/', (req, res) => {
    res.redirect('/login');
});

app.get('/login', (req, res) => {
    res.send(renderBladeView('auth/login'));
});

app.post('/login', (req, res) => {
    res.redirect('/dashboard');
});

app.get('/dashboard', (req, res) => {
    res.send(renderBladeView('dashboard/index', {
        patientsCount: state.patients.length,
        registrationsCount: state.registrations.length,
        medicalRecordsCount: state.medical_records.length,
        revenueTotal: state.payments.reduce((acc, p) => acc + p.total_amount, 0),
        lowStockMedicines: state.medicines.filter(m => m.stock <= m.min_stock),
        recentRegistrations: state.registrations,
    }));
});

// Patients
app.get('/patients', (req, res) => {
    res.send(renderBladeView('patients/index', { patients: state.patients }));
});
app.get('/patients/create', (req, res) => {
    res.send(renderBladeView('patients/create'));
});
app.post('/patients', (req, res) => {
    const newPatient = {
        id: state.patients.length + 1,
        mr_number: `RM-202607-00${state.patients.length + 1}`,
        name: req.body.name || 'Pasien Baru',
        nik: req.body.nik || '317101000000000',
        gender: req.body.gender || 'L',
        birth_date: req.body.birth_date || '1995-01-01',
        phone: req.body.phone || '0812345678',
        address: req.body.address || 'Alamat Pasien',
        bpjs_number: req.body.bpjs_number || null,
        allergies: req.body.allergies || 'Tidak Ada'
    };
    state.patients.unshift(newPatient);
    res.redirect('/patients');
});
app.get('/patients/:id/card', (req, res) => {
    const p = state.patients.find(item => item.id == req.params.id) || state.patients[0];
    res.send(renderBladeView('patients/card', { patient: p }));
});

// Doctors
app.get('/doctors', (req, res) => {
    res.send(renderBladeView('doctors/index', { doctors: state.doctors }));
});
app.get('/doctors/create', (req, res) => {
    res.send(renderBladeView('doctors/create', { clinics: state.clinics }));
});

// Clinics
app.get('/clinics', (req, res) => {
    res.send(renderBladeView('clinics/index', { clinics: state.clinics }));
});
app.get('/clinics/create', (req, res) => {
    res.send(renderBladeView('clinics/create'));
});

// Schedules
app.get('/schedules', (req, res) => {
    res.send(renderBladeView('schedules/index', { schedules: state.schedules }));
});
app.get('/schedules/create', (req, res) => {
    res.send(renderBladeView('schedules/create', { doctors: state.doctors, clinics: state.clinics }));
});

// Registrations
app.get('/registrations', (req, res) => {
    res.send(renderBladeView('registrations/index', { registrations: state.registrations }));
});
app.get('/registrations/create', (req, res) => {
    res.send(renderBladeView('registrations/create', { patients: state.patients, doctors: state.doctors, clinics: state.clinics }));
});
app.get('/registrations/:id/ticket', (req, res) => {
    const reg = state.registrations.find(r => r.id == req.params.id) || state.registrations[0];
    res.send(renderBladeView('registrations/ticket', { registration: reg }));
});

// Medical Records
app.get('/medical-records', (req, res) => {
    res.send(renderBladeView('medical-records/index', { medicalRecords: state.medical_records }));
});
app.get('/medical-records/create', (req, res) => {
    res.send(renderBladeView('medical-records/create', { registrations: state.registrations, treatments: state.treatments, medicines: state.medicines }));
});
app.get('/medical-records/:id', (req, res) => {
    const mr = state.medical_records.find(m => m.id == req.params.id) || state.medical_records[0];
    res.send(renderBladeView('medical-records/show', { medicalRecord: mr }));
});
app.get('/medical-records/:id/pdf', (req, res) => {
    const mr = state.medical_records.find(m => m.id == req.params.id) || state.medical_records[0];
    res.send(renderBladeView('medical-records/pdf', { medicalRecord: mr }));
});

// Medicines & Prescriptions
app.get('/medicines', (req, res) => {
    res.send(renderBladeView('medicines/index', { medicines: state.medicines }));
});
app.get('/medicines/create', (req, res) => {
    res.send(renderBladeView('medicines/create', { suppliers: state.suppliers }));
});
app.get('/prescriptions', (req, res) => {
    res.send(renderBladeView('prescriptions/index', { prescriptions: state.prescriptions }));
});
app.get('/prescriptions/create', (req, res) => {
    res.send(renderBladeView('prescriptions/create', { patients: state.patients, doctors: state.doctors, medicines: state.medicines }));
});
app.get('/prescriptions/:id', (req, res) => {
    const p = state.prescriptions.find(x => x.id == req.params.id) || state.prescriptions[0];
    res.send(renderBladeView('prescriptions/show', { prescription: p }));
});

// Treatments & Payments
app.get('/treatments', (req, res) => {
    res.send(renderBladeView('treatments/index', { treatments: state.treatments }));
});
app.get('/treatments/create', (req, res) => {
    res.send(renderBladeView('treatments/create'));
});
app.get('/payments', (req, res) => {
    res.send(renderBladeView('payments/index', { payments: state.payments }));
});
app.get('/payments/:id', (req, res) => {
    const pay = state.payments.find(x => x.id == req.params.id) || state.payments[0];
    res.send(renderBladeView('payments/show', { payment: pay }));
});
app.get('/payments/:id/invoice', (req, res) => {
    const pay = state.payments.find(x => x.id == req.params.id) || state.payments[0];
    res.send(renderBladeView('payments/invoice', { payment: pay }));
});

// Suppliers & Reports & Settings
app.get('/suppliers', (req, res) => {
    res.send(renderBladeView('suppliers/index', { suppliers: state.suppliers }));
});
app.get('/suppliers/create', (req, res) => {
    res.send(renderBladeView('suppliers/create'));
});
app.get('/reports', (req, res) => {
    res.send(renderBladeView('reports/index'));
});
app.get('/reports/kunjungan', (req, res) => {
    res.send(renderBladeView('reports/kunjungan', { registrations: state.registrations, totalVisits: state.registrations.length, startDate: '2026-07-01', endDate: '2026-07-31' }));
});
app.get('/reports/pendapatan', (req, res) => {
    res.send(renderBladeView('reports/pendapatan', { payments: state.payments, totalRevenue: 107500, startDate: '2026-07-01', endDate: '2026-07-31' }));
});
app.get('/reports/obat', (req, res) => {
    res.send(renderBladeView('reports/obat', { medicines: state.medicines }));
});
app.get('/settings', (req, res) => {
    res.send(renderBladeView('settings/index', { settings: state.settings }));
});

app.listen(PORT, '0.0.0.0', () => {
    console.log(`SIGMA CLINIC Laravel 11 Server running on http://0.0.0.0:${PORT}`);
});
