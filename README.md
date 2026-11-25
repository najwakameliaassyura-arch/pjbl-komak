# KostKu - Sistem Informasi Manajemen Kos & Kontrakan

Aplikasi web modern berbasis **CodeIgniter 4 (PHP 8.2+)**, **MySQL**, **AdminLTE 3**, dan **Dompdf** yang dibangun lengkap untuk memenuhi seluruh kriteria penilaian Tugas Proyek PJBL Blok Komak. Aplikasi siap dijalankan di localhost XAMPP maupun di-deploy ke Free Shared Hosting (seperti InfinityFree / cPanel).

---

## 🌟 Fitur Utama & Kriteria PJBL

### 1. Core CRUD Lengkap & Relasional (Bobot 50%)
- **Manajemen Tipe Kamar:** Input nama tipe, fasilitas, harga sewa per bulan, dan deskripsi.
- **Manajemen Kamar:** Nomor/kode kamar, lantai, tipe kamar, foto kamar dengan validasi upload, dan status (`Tersedia`, `Terisi`, `Perbaikan`).
  - *Otomasi Alur Bisnis:* Status kamar otomatis berubah menjadi `Terisi` saat ada pendaftaran penghuni baru, dan kembali `Tersedia` saat penghuni check-out.
- **Manajemen Penghuni:** Nama lengkap, NIK, No. HP / WhatsApp, pekerjaan, foto KTP, tanggal masuk, tanggal keluar, status (`Aktif`, `Nonaktif`).
  - *Otomasi Alur Bisnis:* Otomatis membuatkan akun login portal bagi penghuni baru dengan enkripsi `password_hash(BCRYPT)`.
  - *Fitur Check-Out:* 1 klik tombol Check-out langsung menonaktifkan status penghuni, menonaktifkan akun login, mencatat tanggal keluar, dan mengembalikan status kamar menjadi `Tersedia`.
- **Manajemen Tagihan Bulanan:**
  - Kode unik tagihan otomatis (`TAG-YYYYMM-XXX`), periode bulan & tahun, nominal mengambil otomatis dari harga tipe kamar, tanggal jatuh tempo, dan status (`Belum Lunas`, `Menunggu Verifikasi`, `Lunas`).
  - **Fitur Unggulan "Generate Tagihan Massal" (1-Klik):** Membuat tagihan bulan berjalan untuk seluruh penghuni aktif secara serentak tanpa duplikasi.
- **Manajemen Pembayaran & Verifikasi:**
  - Pencatatan pembayaran tunai/transfer oleh Admin (langsung berstatus `Diterima` dan tagihan otomatis `Lunas`).
  - Fitur **Upload Bukti Transfer oleh Penghuni** (status menjadi `Menunggu Verifikasi`).
  - Modal **Verifikasi Admin (Terima / Tolak):** Saat pembayaran diverifikasi `Diterima`, status tagihan otomatis berubah menjadi `Lunas`.
- **Manajemen Keluhan / Kerusakan Fasilitas:**
  - Penghuni dapat melapor kerusakan fasilitas kamar beserta upload foto pendukung.
  - Admin dapat memantau status (`Pending`, `Diproses`, `Selesai`) serta memberikan respon / tanggapan yang tampil di dashboard penghuni.

### 2. Kelengkapan Fitur & User Experience (Bobot 15%)
- **Dashboard Admin:** 6 Info box statistik, grafik **Chart.js** tren pendapatan 6 bulan terakhir, tabel cepat tagihan jatuh tempo & pembayaran menunggu verifikasi.
- **Dashboard Penghuni:** Informasi kamar yang sedang ditempati, rincian fasilitas & harga sewa, status tagihan bulan ini, tombol cepat bayar & upload bukti, serta riwayat keluhan kamar.
- **Landing Page Publik:** Profil KostKu, keunggulan fasilitas, katalog kamar yang statusnya `Tersedia` beserta harga sewa & fasilitasnya, dan tombol akses login.
- **DataTables & SweetAlert2:** Pencarian instan dan pagination berbahasa Indonesia, notifikasi flashdata toast, dan konfirmasi hapus/checkout interaktif.

### 3. Cetak / Export Output PDF (Bobot 10%)
Menggunakan `dompdf/dompdf` dengan kop resmi KostKu, tanggal cetak, dan area tanda tangan:
- **Kwitansi Pembayaran (PDF):** Otomatis menyertakan nomor bukti, nominal terbilang rupiah, status LUNAS, dan tanda tangan (dapat dicetak oleh Admin maupun Penghuni).
- **Laporan Pemasukan Keuangan (PDF):** Dapat difilter berdasarkan bulan dan tahun, menampilkan rincian transaksi masuk dan total pendapatan.
- **Laporan Data Penghuni Aktif & Status Kamar (PDF):** Rekapitulasi administrasi seluruh unit kamar dan data penghuni dalam format landscape.

### 4. Security & Role-Based Access Control (Bobot 5%)
- Halaman Login modern AdminLTE 3 dengan CSRF Protection (`csrf_field()`) dan enkripsi password `password_hash` / `password_verify`.
- **CI4 Filters:**
  - `AuthFilter`: Memproteksi route yang membutuhkan sesi login.
  - `RoleFilter`: Memisahkan akses route `admin` (`/admin/*`) dan `penghuni` (`/penghuni/*`).
- **User Management:** Admin dapat menambah user, mengedit role/status, menghapus, serta melakukan reset password secara langsung.
- **Profil & Ganti Password:** Penghuni dapat memperbarui profil dan mengganti password pribadi.

---

## 🔑 Akun Login Demo (Siap Uji)

| Role | Username / Email | Password | Keterangan |
|---|---|---|---|
| **Admin (Owner)** | `admin@kostku.com` (atau `admin`) | `admin123` | Akses penuh ke seluruh fitur dan master data |
| **Penghuni 1** | `budi@kostku.com` (atau `budi`) | `penghuni123` | Menempati Kamar K-101 (Ada tagihan lunas & belum lunas) |
| **Penghuni 2** | `siti@kostku.com` (atau `siti`) | `penghuni123` | Menempati Kamar K-201 (Ada tagihan menunggu verifikasi) |

*Tersedia tombol "Isi Cepat" akun demo di halaman Login.*

---

## 💻 Panduan Menjalankan di Localhost (XAMPP)

1. **Jalankan XAMPP Control Panel:**
   - Start **Apache** dan **MySQL**.

2. **Siapkan Database:**
   - Buka browser ke `http://localhost/phpmyadmin/`.
   - Buat database baru bernama: `kostku`.
   - Klik tab **Import**, pilih file `database/kostku.sql` dari project ini, lalu klik **Import**.

3. **Jalankan Aplikasi:**
   - **Opsi A (Via Spark CLI):**
     Buka terminal / PowerShell di folder project ini:
     ```bash
     php spark serve
     ```
     Buka di browser: `http://localhost:8080/`
   
   - **Opsi B (Via Folder htdocs XAMPP):**
     Salin/pindahkan folder project ini ke `C:\xampp\htdocs\kostku`.
     Buka di browser: `http://localhost/kostku/public/` atau `http://localhost/kostku/`.

---

## 🚀 Panduan Upload ke Free Shared Hosting (InfinityFree / cPanel)

Aplikasi KostKu telah disiapkan agar 100% kompatibel dengan free shared hosting:
1. **Import Database:**
   - Buka phpMyAdmin di cPanel hosting Anda.
   - Buat database baru dan import file `database/kostku.sql`.

2. **Konfigurasi Database Hosting:**
   - Buka file `app/Config/Database.php`.
   - Sesuaikan konfigurasi username, password, dan nama database hosting Anda:
     ```php
     'hostname' => 'sqlxxx.infinityfree.com', // atau localhost
     'username' => 'if0_xxxxxxx',
     'password' => 'password_hosting_anda',
     'database' => 'if0_xxxxxxx_kostku',
     ```

3. **Upload File ke Folder `htdocs`:**
   - Upload seluruh isi folder project ke dalam folder `htdocs` (atau `public_html`) hosting.
   - Root `.htaccess` yang sudah kami sertakan akan otomatis meneruskan request ke folder `public/` dengan aman.
   - Konfigurasi `app/Config/App.php` telah dirancang dinamis otomatis mendeteksi domain hosting Anda tanpa perlu mengatur baseURL secara manual.
   - Folder `public/uploads/` (kamar, ktp, bukti_bayar, keluhan) sudah dilengkapi `.gitkeep` dan izin tulis.

---

## 📁 Struktur Direktori Penting

```text
PJBL BLOK Komak/
├── app/
│   ├── Config/          # Konfigurasi App, Database, Filters, Routes, Security
│   ├── Controllers/     # AuthController, LandingController, Admin/*, Penghuni/*
│   ├── Database/
│   │   ├── Migrations/  # 2026-11-16-000001_CreateKostKuTables.php
│   │   └── Seeds/       # DatabaseSeeder.php
│   ├── Filters/         # AuthFilter.php, RoleFilter.php
│   ├── Models/          # UserModel, KamarModel, PenghuniModel, TagihanModel, dll.
│   └── Views/
│       ├── admin/       # Dashboard, Tipe Kamar, Kamar, Penghuni, Tagihan, Pembayaran, dll.
│       ├── auth/        # Halaman Login modern
│       ├── landing/     # Landing Page Publik (Kamar Tersedia & Fasilitas)
│       ├── layouts/     # AdminLTE 3 Master Layout (DataTables, Chart.js, SweetAlert2)
│       ├── pdf/         # Template Kwitansi, Laporan Pemasukan, & Rekap Penghuni
│       └── penghuni/    # Dashboard, Tagihan & Bayar, Lapor Keluhan, Profil
├── database/
│   └── kostku.sql       # SQL Dump siap import (struktur tabel + dummy data)
├── public/
│   ├── uploads/         # Folder bukti_bayar, kamar, ktp, keluhan
│   ├── index.php
│   └── .htaccess
├── vendor/              # CodeIgniter 4 Framework & dompdf/dompdf (TCPDF Engine)
├── .htaccess            # Root redirector untuk Shared Hosting
└── README.md
```
