<?php
$mysqli = new mysqli("localhost", "root", "", "aabw");

if ($mysqli->connect_error) {
    die("<h2 style='color:red;'>Koneksi MySQL Gagal: " . $mysqli->connect_error . "</h2><p>Pastikan modul <b>MySQL</b> di XAMPP Control Panel sudah di-klik <b>Start</b>!</p>");
}

// 1. Buat Tabel akun2s
$sql2 = "CREATE TABLE IF NOT EXISTS `akun2s` (
  `id_akun2` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_akun2` varchar(6) NOT NULL,
  `nama_akun2` varchar(40) NOT NULL,
  `id_akun1` int(6) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_akun2`),
  KEY `akun2s_id_akun1_foreign` (`id_akun1`),
  CONSTRAINT `akun2s_id_akun1_foreign` FOREIGN KEY (`id_akun1`) REFERENCES `akun1s` (`id_akun1`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$mysqli->query($sql2);

// 2. Buat Tabel akun3s
$sql3 = "CREATE TABLE IF NOT EXISTS `akun3s` (
  `id_akun3` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_akun3` varchar(6) NOT NULL,
  `nama_akun3` varchar(40) NOT NULL,
  `id_akun2` int(6) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_akun3`),
  KEY `akun3s_id_akun2_foreign` (`id_akun2`),
  CONSTRAINT `akun3s_id_akun2_foreign` FOREIGN KEY (`id_akun2`) REFERENCES `akun2s` (`id_akun2`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$mysqli->query($sql3);

// 3. Buat Tabel transaksis
$sqlTr = "CREATE TABLE IF NOT EXISTS `transaksis` (
  `id_transaksi` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kwitansi` varchar(25) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `ketjurnal` varchar(50) NOT NULL,
  PRIMARY KEY (`id_transaksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$mysqli->query($sqlTr);

// 4. Buat Tabel nilais
$sqlNi = "CREATE TABLE IF NOT EXISTS `nilais` (
  `id_nilai` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_transaksi` int(6) UNSIGNED NOT NULL,
  `id_akun3` int(6) UNSIGNED NOT NULL,
  `debit` double DEFAULT 0,
  `kredit` double DEFAULT 0,
  PRIMARY KEY (`id_nilai`),
  KEY `nilais_id_transaksi_foreign` (`id_transaksi`),
  KEY `nilais_id_akun3_foreign` (`id_akun3`),
  CONSTRAINT `nilais_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksis` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nilais_id_akun3_foreign` FOREIGN KEY (`id_akun3`) REFERENCES `akun3s` (`id_akun3`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$mysqli->query($sqlNi);

// 4b. Buat Tabel penyesuaians (Video #13)
$sqlPenyesuaian = "CREATE TABLE IF NOT EXISTS `penyesuaians` (
  `id_penyesuaian` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kwitansi` varchar(25) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_penyesuaian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$mysqli->query($sqlPenyesuaian);

// 4c. Buat Tabel nilai_penyesuaians (Video #13)
$sqlNilaiPenyesuaian = "CREATE TABLE IF NOT EXISTS `nilai_penyesuaians` (
  `id_nilai` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_penyesuaian` int(6) UNSIGNED NOT NULL,
  `id_akun3` int(6) UNSIGNED NOT NULL,
  `debit` double DEFAULT 0,
  `kredit` double DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_nilai`),
  KEY `nilai_penyesuaians_id_penyesuaian_foreign` (`id_penyesuaian`),
  KEY `nilai_penyesuaians_id_akun3_foreign` (`id_akun3`),
  CONSTRAINT `nilai_penyesuaians_id_penyesuaian_foreign` FOREIGN KEY (`id_penyesuaian`) REFERENCES `penyesuaians` (`id_penyesuaian`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nilai_penyesuaians_id_akun3_foreign` FOREIGN KEY (`id_akun3`) REFERENCES `akun3s` (`id_akun3`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$mysqli->query($sqlNilaiPenyesuaian);

// 5. Isi data awal akun2s jika kosong
$check2 = $mysqli->query("SELECT COUNT(*) as total FROM akun2s");
if ($check2 && $check2->fetch_assoc()['total'] == 0) {
    $mysqli->query("INSERT INTO `akun2s` (`id_akun2`, `kode_akun2`, `nama_akun2`, `id_akun1`) VALUES
    (1, '11', 'Aktiva Lancar', 1),
    (2, '12', 'Aktiva Tetap', 1),
    (3, '21', 'Utang Lancar', 2),
    (4, '22', 'Utang Jangka Panjang', 2),
    (5, '31', 'Modal Pemilik', 3),
    (6, '41', 'Pendapatan Usaha', 4),
    (7, '51', 'Beban Operasional', 5);");
}

// 6. Isi data awal akun3s jika kosong
$check3 = $mysqli->query("SELECT COUNT(*) as total FROM akun3s");
if ($check3 && $check3->fetch_assoc()['total'] == 0) {
    $mysqli->query("INSERT INTO `akun3s` (`id_akun3`, `kode_akun3`, `nama_akun3`, `id_akun2`) VALUES
    (1, '1101', 'Kas', 1),
    (2, '1102', 'Piutang Usaha', 1),
    (3, '1103', 'Perlengkapan', 1),
    (4, '1201', 'Peralatan', 2),
    (5, '1202', 'Akumulasi Penyusutan Peralatan', 2),
    (6, '2101', 'Utang Usaha', 3),
    (7, '3101', 'Modal', 5),
    (8, '3102', 'Prive', 5),
    (9, '4101', 'Pendapatan Jasa', 6),
    (10, '5101', 'Beban Gaji', 7),
    (11, '5102', 'Beban Sewa', 7);");
}

echo "<div style='font-family:sans-serif;padding:30px;text-align:center;'>";
echo "<h1 style='color:green;'>Berhasil! Seluruh Tabel Telah Dibuat (akun2s, akun3s, transaksis, nilais)</h1>";
echo "<p>Database sudah siap dan data awal telah terisi.</p>";
echo "<div style='margin-top:20px;'>";
echo "<a href='http://localhost:8080/penyesuaian' style='display:inline-block;padding:12px 25px;background:#ffc107;color:#212529;text-decoration:none;border-radius:6px;font-weight:bold;margin:10px;'>Buka Penyesuaian (Port 8080)</a>";
echo "<a href='http://localhost/aabw/public/penyesuaian' style='display:inline-block;padding:12px 25px;background:#17a2b8;color:#fff;text-decoration:none;border-radius:6px;font-weight:bold;margin:10px;'>Buka Penyesuaian (Apache XAMPP)</a>";
echo "</div>";
echo "</div>";
