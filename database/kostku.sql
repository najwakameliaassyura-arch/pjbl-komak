-- ============================================================
-- KostKu - Sistem Informasi Manajemen Kos & Kontrakan
-- Database Dump for MySQL / phpMyAdmin Free Hosting
-- PHP 8.1+ / CodeIgniter 4 Compatible
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','penghuni') NOT NULL DEFAULT 'penghuni',
  `foto_profil` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dumping data for table `users`
-- Password for admin: admin123
-- Password for budi & siti: penghuni123
-- --------------------------------------------------------

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `email`, `password`, `role`, `foto_profil`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Pengelola KostKu (Owner)', 'admin', 'admin@kostku.com', '$2y$10$w/rK69dnnYgpzy27I5Y41unz4f7/zDOzU.FllqvhVBDtOmbJlB5QO', 'admin', NULL, 1, '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(2, 'Budi Santoso', 'budi', 'budi@kostku.com', '$2y$10$Ewze33CfjWBDxI.Vv/LlD./QuugJbLTvDKO4.W2lffCK40kxq1Ds6', 'penghuni', NULL, 1, '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(3, 'Siti Rahmawati', 'siti', 'siti@kostku.com', '$2y$10$Ewze33CfjWBDxI.Vv/LlD./QuugJbLTvDKO4.W2lffCK40kxq1Ds6', 'penghuni', NULL, 1, '2026-11-16 08:00:00', '2026-11-16 08:00:00');

-- --------------------------------------------------------
-- Table structure for table `tipe_kamar`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tipe_kamar`;
CREATE TABLE `tipe_kamar` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_tipe` varchar(100) NOT NULL,
  `harga_bulanan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fasilitas` text NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dumping data for table `tipe_kamar`
-- --------------------------------------------------------

INSERT INTO `tipe_kamar` (`id`, `nama_tipe`, `harga_bulanan`, `fasilitas`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Reguler Standard', 750000.00, 'Kasur Single Foam, Lemari Pakaian 2 Pintu, Meja & Kursi Belajar, Kipas Angin Dinding, Kamar Mandi Luar Bersih, Listrik Termasuk Standar, Free WiFi 50 Mbps.', 'Pilihan kamar hemat, nyaman dan sirkulasi udara baik, sangat cocok untuk mahasiswa atau perantau yang mengutamakan ketenangan belajar.', '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(2, 'Deluxe AC', 1200000.00, 'Kasur Springbed Queen, AC Inverter Hemat Energi, Lemari Pakaian Cermin, Meja Kerja Minimalis, Free High Speed WiFi, Kamar Mandi Luar Khusus Lantai 2, Dapur Bersama.', 'Kamar sejuk dan luas dengan perabotan modern, cocok untuk mahasiswa tingkat akhir atau karyawan yang menginginkan kenyamanan ekstra.', '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(3, 'VIP Suite Eksklusif', 1750000.00, 'Kasur King Premium, Smart TV 32 inch, AC 1 PK, Kamar Mandi Dalam (Shower Panas/Dingin & Kloset Duduk TOTO), Meja Kerja Ergonomis, Lemari Besar, Balkon Pribadi, Akses Card Pintu Utama.', 'Kamar kelas tertinggi dengan fasilitas setara hotel berbintang, privasi penuh dengan kamar mandi dalam mewah dan balkon pemandangan kota.', '2026-11-16 08:00:00', '2026-11-16 08:00:00');

-- --------------------------------------------------------
-- Table structure for table `kamar`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `kamar`;
CREATE TABLE `kamar` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipe_id` int(11) UNSIGNED NOT NULL,
  `nomor_kamar` varchar(50) NOT NULL,
  `lantai` varchar(20) NOT NULL DEFAULT 'Lantai 1',
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('Tersedia','Terisi','Perbaikan') NOT NULL DEFAULT 'Tersedia',
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_kamar` (`nomor_kamar`),
  KEY `tipe_id` (`tipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dumping data for table `kamar`
-- --------------------------------------------------------

INSERT INTO `kamar` (`id`, `tipe_id`, `nomor_kamar`, `lantai`, `foto`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 'K-101', 'Lantai 1', NULL, 'Terisi', 'Dekat pintu masuk utama, sirkulasi udara jendela luar.', '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(2, 1, 'K-102', 'Lantai 1', NULL, 'Tersedia', 'Kamar siap huni, baru saja dibersihkan.', '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(3, 1, 'K-103', 'Lantai 1', NULL, 'Tersedia', 'Posisi dekat taman belakang yang asri.', '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(4, 2, 'K-201', 'Lantai 2', NULL, 'Terisi', 'Ditempati Siti Rahmawati sejak September 2026.', '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(5, 2, 'K-202', 'Lantai 2', NULL, 'Tersedia', 'Kondisi prima, AC baru servis dan dingin optimal.', '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(6, 2, 'K-203', 'Lantai 2', NULL, 'Perbaikan', 'Pengecatan ulang dinding dan pergantian gorden jendela.', '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(7, 3, 'K-301', 'Lantai 3', NULL, 'Tersedia', 'Kamar VIP pojok dengan pencahayaan alami & balkon luas.', '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(8, 3, 'K-302', 'Lantai 3', NULL, 'Tersedia', 'Kamar VIP tengah, sangat tenang dan kedap suara.', '2026-11-16 08:00:00', '2026-11-16 08:00:00');

-- --------------------------------------------------------
-- Table structure for table `penghuni`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `penghuni`;
CREATE TABLE `penghuni` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `kamar_id` int(11) UNSIGNED DEFAULT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `nik` varchar(30) NOT NULL,
  `no_hp` varchar(30) NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `tgl_masuk` date NOT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `status` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `kamar_id` (`kamar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dumping data for table `penghuni`
-- --------------------------------------------------------

INSERT INTO `penghuni` (`id`, `user_id`, `kamar_id`, `nama_lengkap`, `nik`, `no_hp`, `pekerjaan`, `foto_ktp`, `tgl_masuk`, `tgl_keluar`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Budi Santoso', '3273011204990001', '081234567890', 'Mahasiswa Teknik Informatika', NULL, '2026-08-01', NULL, 'Aktif', '2026-11-16 08:00:00', '2026-11-16 08:00:00'),
(2, 3, 4, 'Siti Rahmawati', '3273015509980002', '085712345678', 'Software Engineer (Karyawan Swasta)', NULL, '2026-09-01', NULL, 'Aktif', '2026-11-16 08:00:00', '2026-11-16 08:00:00');

-- --------------------------------------------------------
-- Table structure for table `tagihan`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tagihan`;
CREATE TABLE `tagihan` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_tagihan` varchar(50) NOT NULL,
  `penghuni_id` int(11) UNSIGNED NOT NULL,
  `kamar_id` int(11) UNSIGNED NOT NULL,
  `bulan` int(2) NOT NULL,
  `tahun` int(4) NOT NULL,
  `nominal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `jatuh_tempo` date NOT NULL,
  `status` enum('Belum Lunas','Menunggu Verifikasi','Lunas') NOT NULL DEFAULT 'Belum Lunas',
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_tagihan` (`kode_tagihan`),
  KEY `penghuni_id` (`penghuni_id`),
  KEY `kamar_id` (`kamar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dumping data for table `tagihan`
-- --------------------------------------------------------

INSERT INTO `tagihan` (`id`, `kode_tagihan`, `penghuni_id`, `kamar_id`, `bulan`, `tahun`, `nominal`, `jatuh_tempo`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'TAG-202610-001', 1, 1, 10, 2026, 750000.00, '2026-10-10', 'Lunas', 'Tagihan sewa kamar Oktober 2026', '2026-10-01 08:00:00', '2026-10-02 10:15:00'),
(2, 'TAG-202610-002', 2, 4, 10, 2026, 1200000.00, '2026-10-10', 'Lunas', 'Tagihan sewa kamar Oktober 2026', '2026-10-01 08:00:00', '2026-10-03 14:20:00'),
(3, 'TAG-202611-001', 1, 1, 11, 2026, 750000.00, '2026-11-20', 'Belum Lunas', 'Tagihan sewa kamar November 2026', '2026-11-01 08:00:00', '2026-11-01 08:00:00'),
(4, 'TAG-202611-002', 2, 4, 11, 2026, 1200000.00, '2026-11-20', 'Menunggu Verifikasi', 'Tagihan sewa kamar November 2026', '2026-11-01 08:00:00', '2026-11-15 11:30:00');

-- --------------------------------------------------------
-- Table structure for table `pembayaran`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `pembayaran`;
CREATE TABLE `pembayaran` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_pembayaran` varchar(50) NOT NULL,
  `tagihan_id` int(11) UNSIGNED NOT NULL,
  `tgl_bayar` date NOT NULL,
  `nominal_bayar` decimal(12,2) NOT NULL DEFAULT 0.00,
  `metode_bayar` enum('Transfer','Tunai') NOT NULL DEFAULT 'Transfer',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `status_verifikasi` enum('Menunggu Verifikasi','Diterima','Ditolak') NOT NULL DEFAULT 'Menunggu Verifikasi',
  `catatan_admin` text DEFAULT NULL,
  `verifikasi_oleh` int(11) UNSIGNED DEFAULT NULL,
  `tgl_verifikasi` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_pembayaran` (`kode_pembayaran`),
  KEY `tagihan_id` (`tagihan_id`),
  KEY `verifikasi_oleh` (`verifikasi_oleh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dumping data for table `pembayaran`
-- --------------------------------------------------------

INSERT INTO `pembayaran` (`id`, `kode_pembayaran`, `tagihan_id`, `tgl_bayar`, `nominal_bayar`, `metode_bayar`, `bukti_bayar`, `status_verifikasi`, `catatan_admin`, `verifikasi_oleh`, `tgl_verifikasi`, `created_at`, `updated_at`) VALUES
(1, 'PAY-202610-001', 1, '2026-10-02', 750000.00, 'Transfer', NULL, 'Diterima', 'Transfer via BCA masuk valid.', 1, '2026-10-02 10:15:00', '2026-10-02 09:30:00', '2026-10-02 10:15:00'),
(2, 'PAY-202610-002', 2, '2026-10-03', 1200000.00, 'Tunai', NULL, 'Diterima', 'Diterima langsung tunai di kantor pengelola.', 1, '2026-10-03 14:20:00', '2026-10-03 14:20:00', '2026-10-03 14:20:00'),
(3, 'PAY-202611-001', 4, '2026-11-15', 1200000.00, 'Transfer', NULL, 'Menunggu Verifikasi', NULL, NULL, NULL, '2026-11-15 11:30:00', '2026-11-15 11:30:00');

-- --------------------------------------------------------
-- Table structure for table `keluhan`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `keluhan`;
CREATE TABLE `keluhan` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `penghuni_id` int(11) UNSIGNED NOT NULL,
  `kamar_id` int(11) UNSIGNED NOT NULL,
  `judul_keluhan` varchar(200) NOT NULL,
  `deskripsi_keluhan` text NOT NULL,
  `foto_keluhan` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Diproses','Selesai') NOT NULL DEFAULT 'Pending',
  `tanggapan_admin` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penghuni_id` (`penghuni_id`),
  KEY `kamar_id` (`kamar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Dumping data for table `keluhan`
-- --------------------------------------------------------

INSERT INTO `keluhan` (`id`, `penghuni_id`, `kamar_id`, `judul_keluhan`, `deskripsi_keluhan`, `foto_keluhan`, `status`, `tanggapan_admin`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Keran Wastafel Kamar Mandi Menetes Bocor', 'Kran wastafel di depan kamar tidak bisa ditutup rapat dan terus meneteskan air, mohon bantuan perbaikannya.', NULL, 'Diproses', 'Teknisi sanitasi sudah dihubungi dan dijadwalkan datang untuk mengganti karet kran besok pagi jam 10.00 WIB.', '2026-11-14 09:00:00', '2026-11-14 13:00:00'),
(2, 2, 4, 'Baterai Remote AC Habis', 'Remote AC kamar K-201 layarnya redup dan tidak merespons tombol suhu.', NULL, 'Selesai', 'Baterai remot sudah diganti baru tipe AAA oleh staf pengelola kos.', '2026-11-10 16:30:00', '2026-11-10 17:15:00');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
