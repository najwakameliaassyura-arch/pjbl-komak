<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Users
        $usersData = [
            [
                'id'           => 1,
                'nama_lengkap' => 'Pengelola KostKu (Owner)',
                'username'     => 'admin',
                'email'        => 'admin@kostku.com',
                'password'     => password_hash('admin123', PASSWORD_BCRYPT),
                'role'         => 'admin',
                'foto_profil'  => null,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'id'           => 2,
                'nama_lengkap' => 'Budi Santoso',
                'username'     => 'budi',
                'email'        => 'budi@kostku.com',
                'password'     => password_hash('penghuni123', PASSWORD_BCRYPT),
                'role'         => 'penghuni',
                'foto_profil'  => null,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'id'           => 3,
                'nama_lengkap' => 'Siti Rahmawati',
                'username'     => 'siti',
                'email'        => 'siti@kostku.com',
                'password'     => password_hash('penghuni123', PASSWORD_BCRYPT),
                'role'         => 'penghuni',
                'foto_profil'  => null,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];
        $db->table('users')->insertBatch($usersData);

        // 2. Tipe Kamar
        $tipeKamarData = [
            [
                'id'            => 1,
                'nama_tipe'     => 'Reguler Standard',
                'harga_bulanan' => 750000.00,
                'fasilitas'     => 'Kasur Single Foam, Lemari Pakaian 2 Pintu, Meja & Kursi Belajar, Kipas Angin Dinding, Kamar Mandi Luar Bersih, Listrik Termasuk Standar, Free WiFi 50 Mbps.',
                'deskripsi'     => 'Pilihan kamar hemat, nyaman dan sirkulasi udara baik, sangat cocok untuk mahasiswa atau perantau yang mengutamakan ketenangan belajar.',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'id'            => 2,
                'nama_tipe'     => 'Deluxe AC',
                'harga_bulanan' => 1200000.00,
                'fasilitas'     => 'Kasur Springbed Queen, AC Inverter Hemat Energi, Lemari Pakaian Cermin, Meja Kerja Minimalis, Free High Speed WiFi, Kamar Mandi Luar Khusus Lantai 2, Dapur Bersama.',
                'deskripsi'     => 'Kamar sejuk dan luas dengan perabotan modern, cocok untuk mahasiswa tingkat akhir atau karyawan yang menginginkan kenyamanan ekstra.',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'id'            => 3,
                'nama_tipe'     => 'VIP Suite Eksklusif',
                'harga_bulanan' => 1750000.00,
                'fasilitas'     => 'Kasur King Premium, Smart TV 32 inch, AC 1 PK, Kamar Mandi Dalam (Shower Panas/Dingin & Kloset Duduk TOTO), Meja Kerja Ergonomis, Lemari Besar, Balkon Pribadi, Akses Card Pintu Utama.',
                'deskripsi'     => 'Kamar kelas tertinggi dengan fasilitas setara hotel berbintang, privasi penuh dengan kamar mandi dalam mewah dan balkon pemandangan kota.',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];
        $db->table('tipe_kamar')->insertBatch($tipeKamarData);

        // 3. Kamar (8 kamar)
        $kamarData = [
            [
                'id'          => 1,
                'tipe_id'     => 1,
                'nomor_kamar' => 'K-101',
                'lantai'      => 'Lantai 1',
                'foto'        => null,
                'status'      => 'Terisi',
                'keterangan'  => 'Dekat pintu masuk utama, sirkulasi udara jendela luar.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 2,
                'tipe_id'     => 1,
                'nomor_kamar' => 'K-102',
                'lantai'      => 'Lantai 1',
                'foto'        => null,
                'status'      => 'Tersedia',
                'keterangan'  => 'Kamar siap huni, baru saja dibersihkan.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 3,
                'tipe_id'     => 1,
                'nomor_kamar' => 'K-103',
                'lantai'      => 'Lantai 1',
                'foto'        => null,
                'status'      => 'Tersedia',
                'keterangan'  => 'Posisi dekat taman belakang yang asri.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 4,
                'tipe_id'     => 2,
                'nomor_kamar' => 'K-201',
                'lantai'      => 'Lantai 2',
                'foto'        => null,
                'status'      => 'Terisi',
                'keterangan'  => 'Ditempati Siti Rahmawati sejak September 2026.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 5,
                'tipe_id'     => 2,
                'nomor_kamar' => 'K-202',
                'lantai'      => 'Lantai 2',
                'foto'        => null,
                'status'      => 'Tersedia',
                'keterangan'  => 'Kondisi prima, AC baru servis dan dingin optimal.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 6,
                'tipe_id'     => 2,
                'nomor_kamar' => 'K-203',
                'lantai'      => 'Lantai 2',
                'foto'        => null,
                'status'      => 'Perbaikan',
                'keterangan'  => 'Pengecatan ulang dinding dan pergantian gorden jendela.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 7,
                'tipe_id'     => 3,
                'nomor_kamar' => 'K-301',
                'lantai'      => 'Lantai 3',
                'foto'        => null,
                'status'      => 'Tersedia',
                'keterangan'  => 'Kamar VIP pojok dengan pencahayaan alami & balkon luas.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'id'          => 8,
                'tipe_id'     => 3,
                'nomor_kamar' => 'K-302',
                'lantai'      => 'Lantai 3',
                'foto'        => null,
                'status'      => 'Tersedia',
                'keterangan'  => 'Kamar VIP tengah, sangat tenang dan kedap suara.',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];
        $db->table('kamar')->insertBatch($kamarData);

        // 4. Penghuni
        $penghuniData = [
            [
                'id'           => 1,
                'user_id'      => 2, // Budi
                'kamar_id'     => 1, // K-101
                'nama_lengkap' => 'Budi Santoso',
                'nik'          => '3273011204990001',
                'no_hp'        => '081234567890',
                'pekerjaan'    => 'Mahasiswa Teknik Informatika',
                'foto_ktp'     => null,
                'tgl_masuk'    => '2026-08-01',
                'tgl_keluar'   => null,
                'status'       => 'Aktif',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'id'           => 2,
                'user_id'      => 3, // Siti
                'kamar_id'     => 4, // K-201
                'nama_lengkap' => 'Siti Rahmawati',
                'nik'          => '3273015509980002',
                'no_hp'        => '085712345678',
                'pekerjaan'    => 'Software Engineer (Karyawan Swasta)',
                'foto_ktp'     => null,
                'tgl_masuk'    => '2026-09-01',
                'tgl_keluar'   => null,
                'status'       => 'Aktif',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];
        $db->table('penghuni')->insertBatch($penghuniData);

        // 5. Tagihan
        $tagihanData = [
            [
                'id'           => 1,
                'kode_tagihan' => 'TAG-202610-001',
                'penghuni_id'  => 1,
                'kamar_id'     => 1,
                'bulan'        => 10,
                'tahun'        => 2026,
                'nominal'      => 750000.00,
                'jatuh_tempo'  => '2026-10-10',
                'status'       => 'Lunas',
                'keterangan'   => 'Tagihan sewa kamar Oktober 2026',
                'created_at'   => '2026-10-01 08:00:00',
                'updated_at'   => '2026-10-02 10:15:00',
            ],
            [
                'id'           => 2,
                'kode_tagihan' => 'TAG-202610-002',
                'penghuni_id'  => 2,
                'kamar_id'     => 4,
                'bulan'        => 10,
                'tahun'        => 2026,
                'nominal'      => 1200000.00,
                'jatuh_tempo'  => '2026-10-10',
                'status'       => 'Lunas',
                'keterangan'   => 'Tagihan sewa kamar Oktober 2026',
                'created_at'   => '2026-10-01 08:00:00',
                'updated_at'   => '2026-10-03 14:20:00',
            ],
            [
                'id'           => 3,
                'kode_tagihan' => 'TAG-202611-001',
                'penghuni_id'  => 1,
                'kamar_id'     => 1,
                'bulan'        => 11,
                'tahun'        => 2026,
                'nominal'      => 750000.00,
                'jatuh_tempo'  => '2026-11-20',
                'status'       => 'Belum Lunas',
                'keterangan'   => 'Tagihan sewa kamar November 2026',
                'created_at'   => '2026-11-01 08:00:00',
                'updated_at'   => '2026-11-01 08:00:00',
            ],
            [
                'id'           => 4,
                'kode_tagihan' => 'TAG-202611-002',
                'penghuni_id'  => 2,
                'kamar_id'     => 4,
                'bulan'        => 11,
                'tahun'        => 2026,
                'nominal'      => 1200000.00,
                'jatuh_tempo'  => '2026-11-20',
                'status'       => 'Menunggu Verifikasi',
                'keterangan'   => 'Tagihan sewa kamar November 2026',
                'created_at'   => '2026-11-01 08:00:00',
                'updated_at'   => '2026-11-15 11:30:00',
            ],
        ];
        $db->table('tagihan')->insertBatch($tagihanData);

        // 6. Pembayaran
        $pembayaranData = [
            [
                'id'                => 1,
                'kode_pembayaran'   => 'PAY-202610-001',
                'tagihan_id'        => 1,
                'tgl_bayar'         => '2026-10-02',
                'nominal_bayar'     => 750000.00,
                'metode_bayar'      => 'Transfer',
                'bukti_bayar'       => null,
                'status_verifikasi' => 'Diterima',
                'catatan_admin'     => 'Transfer via BCA masuk valid.',
                'verifikasi_oleh'   => 1,
                'tgl_verifikasi'    => '2026-10-02 10:15:00',
                'created_at'        => '2026-10-02 09:30:00',
                'updated_at'        => '2026-10-02 10:15:00',
            ],
            [
                'id'                => 2,
                'kode_pembayaran'   => 'PAY-202610-002',
                'tagihan_id'        => 2,
                'tgl_bayar'         => '2026-10-03',
                'nominal_bayar'     => 1200000.00,
                'metode_bayar'      => 'Tunai',
                'bukti_bayar'       => null,
                'status_verifikasi' => 'Diterima',
                'catatan_admin'     => 'Diterima langsung tunai di kantor pengelola.',
                'verifikasi_oleh'   => 1,
                'tgl_verifikasi'    => '2026-10-03 14:20:00',
                'created_at'        => '2026-10-03 14:20:00',
                'updated_at'        => '2026-10-03 14:20:00',
            ],
            [
                'id'                => 3,
                'kode_pembayaran'   => 'PAY-202611-001',
                'tagihan_id'        => 4, // Siti Tagihan Nov
                'tgl_bayar'         => '2026-11-15',
                'nominal_bayar'     => 1200000.00,
                'metode_bayar'      => 'Transfer',
                'bukti_bayar'       => null,
                'status_verifikasi' => 'Menunggu Verifikasi',
                'catatan_admin'     => null,
                'verifikasi_oleh'   => null,
                'tgl_verifikasi'    => null,
                'created_at'        => '2026-11-15 11:30:00',
                'updated_at'        => '2026-11-15 11:30:00',
            ],
        ];
        $db->table('pembayaran')->insertBatch($pembayaranData);

        // 7. Keluhan
        $keluhanData = [
            [
                'id'                => 1,
                'penghuni_id'       => 1, // Budi
                'kamar_id'          => 1,
                'judul_keluhan'     => 'Keran Wastafel Kamar Mandi Menetes Bocor',
                'deskripsi_keluhan' => 'Kran wastafel di depan kamar tidak bisa ditutup rapat dan terus meneteskan air, mohon bantuan perbaikannya.',
                'foto_keluhan'      => null,
                'status'            => 'Diproses',
                'tanggapan_admin'   => 'Teknisi sanitasi sudah dihubungi dan dijadwalkan datang untuk mengganti karet kran besok pagi jam 10.00 WIB.',
                'created_at'        => '2026-11-14 09:00:00',
                'updated_at'        => '2026-11-14 13:00:00',
            ],
            [
                'id'                => 2,
                'penghuni_id'       => 2, // Siti
                'kamar_id'          => 4,
                'judul_keluhan'     => 'Baterai Remote AC Habis',
                'deskripsi_keluhan' => 'Remote AC kamar K-201 layarnya redup dan tidak merespons tombol suhu.',
                'foto_keluhan'      => null,
                'status'            => 'Selesai',
                'tanggapan_admin'   => 'Baterai remot sudah diganti baru tipe AAA oleh staf pengelola kos.',
                'created_at'        => '2026-11-10 16:30:00',
                'updated_at'        => '2026-11-10 17:15:00',
            ],
        ];
        $db->table('keluhan')->insertBatch($keluhanData);
    }
}
