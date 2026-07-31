# 🏥 SIGMA CLINIC — Sistem Informasi Manajemen Klinik & Pelayanan Medis

SIGMA CLINIC adalah aplikasi Sistem Informasi Manajemen Klinik (SIMK) full-stack berbasis Laravel & Node.js yang dirancang untuk mengelola seluruh operasional pelayanan kesehatan secara efisien, terintegrasi, dan real-time.

---

## 🌟 Fitur Utama Sistem

1. **Pendaftaran & Antrian Pasien Real-time**:
    - Pendaftaran kunjungan pasien baru & lama.
    - Pilihan jenis kunjungan (Umum / Mandiri, BPJS Kesehatan, Asuransi Swasta).
    - Pengisian keluhan utama pasien.
    - Cetak **Strip Antrian Pasien** otomatis dengan nomor registrasi, poli tujuan, dan estimasi waktu.

2. **Manajemen Jadwal Dokter & Kuota Harian**:
    - Jadwal praktek per hari (Senin - Minggu).
    - Perhitungan otomatis sisa kuota pasien khusus untuk **hari berjalan (real-time)** tanpa mengurangi kuota di hari lain.

3. **Pemeriksaan Dokter (Rekam Medis / EMR)**:
    - Diagnosa medis (ICD-10 / Text).
    - Catatan resep obat langsung terhubung ke bagian Farmasi.
    - Riwayat rekam medis pasien terintegrasi (Nomor RM).

4. **Apotek & Inventoris Obat**:
    - Pengeluaran obat berdasarkan resep dokter.
    - Manajemen stok obat, kategori, harga jual, dan stok minimum alert.
    - Riwayat transaksi penerimaan & pengeluaran obat.

5. **Kasir & Kasir Pembayaran**:
    - Pembayaran biaya konsultasi, tindakan medis, dan obat-obatan.
    - Cetak struk/invoice pembayaran resmi klinik.

6. **Manajemen Pengguna & Hak Akses (User Management)**:
    - Pengelolaan akun login seluruh staf.
    - Sistem Multi-Role (Administrator, Dokter, Resepsionis, Apoteker).
    - Fitur lihat password (_Show/Hide Password toggle icon_) pada form login dan kelola akun.
    - Relasi profil dokter ke Poli Spesialis.

7. **Laporan & Statistik**:
    - Laporan pendapatan bulanan/harian.
    - Laporan jumlah kunjungan pasien per poli & jenis jaminan.
    - Laporan pemakaian & stok obat.

---

## 🔑 Kredensial Login Demo (Default)

Semua akun demo menggunakan password default: **`password`**

| Role / Peran      | Email Kredensial               | Hak Akses Utama                                        |
| :---------------- | :----------------------------- | :----------------------------------------------------- |
| **Administrator** | `admin@sigmaclinic.com`        | Akses penuh seluruh sistem, user management, & laporan |
| **Dokter Klinik** | `dokter.andri@sigmaclinic.com` | Pemeriksaan pasien, diagnosa, & input resep obat       |
| **Resepsionis**   | `resepsionis@sigmaclinic.com`  | Pendaftaran pasien, cetak antrian, & kasir pembayaran  |
| **Apoteker**      | `apoteker@sigmaclinic.com`     | Pengelolaan resep, penyiapan obat, & stok obat         |

---

## 💻 Panduan Jalankan Aplikasi di Lokal (Local Setup)

### Persyaratan Sistem (Prerequisites)

- **Node.js**: v18.x atau lebih baru
- **PHP**: v8.2 atau lebih baru (jika menggunakan runtime Laravel PHP)
- **Composer**: v2.x (jika menggunakan Laravel PHP)
- **Database**: SQLite (default) atau MySQL / PostgreSQL

---

### Langkah-langkah Instalasi & Run Local:

#### 1. Clone / Download Repository

```bash
git clone <repository-url>
cd sigma-clinic
```

#### 2. Konfigurasi Environment File

Duplikat file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

#### 3. Install Dependensi Node.js

```bash
npm install
```

#### 4. Jalankan Server Aplikasi

Jalankan dev server dengan command:

```bash
npm run dev
```

Atau jika menggunakan aplikasi bawaan:

```bash
npm start
```

Buka browser dan akses: `http://localhost:3000` atau `http://localhost:8000`

---

### Alternative: Opsi Jalankan dengan PHP Native / Artisan

Jika Anda ingin menjalankan backend menggunakan PHP Artisan native:

1. **Install Composer Dependencies**:
    ```bash
    composer install
    ```
2. **Generate Application Key**:
    ```bash
    php artisan key:generate
    ```
3. **Jalankan Database Migration & Seeder Data Demo**:
    ```bash
    php artisan migrate:fresh --seed
    ```
4. **Jalankan Server Laravel**:
    ```bash
    php artisan serve
    ```
5. **Jalankan Asset Build (Vite/Tailwind)**:
    ```bash
    npm run dev
    ```

---

## 📁 Struktur Folder Utama

```
├── app/
│   ├── Http/
│   │   ├── Controllers/     # UserController, RegistrationController, DoctorController, dll.
│   │   └── Middleware/      # RoleMiddleware, AuthenticatedMiddleware
│   └── Models/              # User, Patient, Doctor, Registration, Medicine, dll.
├── database/
│   ├── migrations/          # Struktur tabel database
│   └── seeders/             # Data seeder otomatis
├── resources/
│   ├── views/               # Blade Templates (auth, users, registrations, dll.)
│   └── css/                 # Styling Tailwind CSS
├── routes/
│   └── web.php              # Routing aplikasi
├── server.ts                # Entry point Node.js server container
└── README.md                # Dokumentasi petunjuk aplikasi
```

---

## 🛡️ Keamanan & Privasi

- Seluruh password di-hash menggunakan algoritma **Bcrypt**.
- Proteksi CSRF pada seluruh form input.
- Validasi peran (Role-based Access Control) terisolasi pada tingkat Route Middleware & Controller.

---

## 📄 Lisensi

Sistem ini dikembangkan untuk kebutuhan manajemen operasional klinik kesehatan modern. Hak Cipta © 2026 SIGMA CLINIC.
