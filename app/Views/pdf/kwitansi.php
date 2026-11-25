<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #222;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .kop {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop h2 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
            color: #1a365d;
            letter-spacing: 1px;
        }
        .kop p {
            margin: 3px 0 0 0;
            font-size: 9pt;
            color: #555;
        }
        .title-box {
            text-align: center;
            margin-bottom: 25px;
        }
        .title-box h3 {
            margin: 0;
            text-decoration: underline;
            font-size: 14pt;
            letter-spacing: 0.5px;
        }
        .title-box .nomor {
            font-size: 10pt;
            color: #444;
            margin-top: 4px;
        }
        .table-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-info td {
            padding: 7px 5px;
            vertical-align: top;
        }
        .label {
            width: 28%;
            font-weight: bold;
        }
        .titik {
            width: 3%;
        }
        .val {
            width: 69%;
        }
        .terbilang-box {
            background-color: #f1f5f9;
            border: 1px dashed #94a3b8;
            padding: 8px 12px;
            font-style: italic;
            font-weight: bold;
            color: #0f172a;
        }
        .nominal-box {
            font-size: 15pt;
            font-weight: bold;
            color: #047857;
            padding: 6px 12px;
            background-color: #ecfdf5;
            border: 2px solid #10b981;
            display: inline-block;
            margin-top: 5px;
        }
        .lunas-stamp {
            display: inline-block;
            border: 2px solid #059669;
            color: #059669;
            padding: 4px 12px;
            font-weight: bold;
            font-size: 11pt;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .ttd-table {
            width: 100%;
            margin-top: 30px;
        }
        .ttd-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
        }
    </style>
</head>
<body>

    <!-- Kop KostKu -->
    <div class="kop">
        <h2>KostKu Management</h2>
        <p>Sistem Informasi Manajemen Kos & Kontrakan Modern Terpadu</p>
        <p>Jl. Terusan Telekomunikasi No. 1219, Bandung | Telp/WA: 0812-3456-7890 | Email: admin@kostku.com</p>
    </div>

    <!-- Judul Kwitansi -->
    <div class="title-box">
        <h3>KWITANSI PEMBAYARAN SEWA</h3>
        <div class="nomor">No. Transaksi: <strong><?= esc($pembayaran['kode_pembayaran'] ?? $tagihan['kode_tagihan']) ?></strong></div>
    </div>

    <!-- Tabel Data Kwitansi -->
    <table class="table-info">
        <tr>
            <td class="label">Telah Diterima Dari</td>
            <td class="titik">:</td>
            <td class="val font-weight-bold">
                <strong><?= esc($tagihan['nama_penghuni']) ?></strong> (NIK: <?= esc($tagihan['nik']) ?>)
            </td>
        </tr>
        <tr>
            <td class="label">Unit Kamar Ditempati</td>
            <td class="titik">:</td>
            <td class="val">
                <strong>Kamar <?= esc($tagihan['nomor_kamar']) ?></strong> - Tipe: <?= esc($tagihan['nama_tipe']) ?>
            </td>
        </tr>
        <tr>
            <td class="label">Untuk Pembayaran</td>
            <td class="titik">:</td>
            <td class="val">
                <?php 
                $indonesianMonths = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                $blnStr = $indonesianMonths[(int)$tagihan['bulan']] ?? $tagihan['bulan'];
                ?>
                Sewa Kamar Periode Bulan <strong><?= $blnStr ?> <?= esc($tagihan['tahun']) ?></strong>
            </td>
        </tr>
        <tr>
            <td class="label">Metode & Tanggal Bayar</td>
            <td class="titik">:</td>
            <td class="val">
                <?= esc($pembayaran['metode_bayar'] ?? 'Transfer') ?> &bull; Tanggal: <?= date('d F Y', strtotime($pembayaran['tgl_bayar'] ?? $tagihan['updated_at'])) ?>
            </td>
        </tr>
        <tr>
            <td class="label">Terbilang</td>
            <td class="titik">:</td>
            <td class="val">
                <div class="terbilang-box">
                    # <?= esc($terbilang) ?> #
                </div>
            </td>
        </tr>
        <tr>
            <td class="label">Jumlah Nominal</td>
            <td class="titik">:</td>
            <td class="val">
                <div class="nominal-box">
                    Rp <?= number_format($tagihan['nominal'], 0, ',', '.') ?>,-
                </div>
                <div style="margin-top: 8px;">
                    <span class="lunas-stamp">&#10004; LUNAS</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tanda Tangan Area -->
    <table class="ttd-table">
        <tr>
            <td>
                Penyewa / Penghuni,<br><br><br><br><br>
                <strong>( <?= esc($tagihan['nama_penghuni']) ?> )</strong>
            </td>
            <td>
                Bandung, <?= esc($tglCetak) ?><br>
                Pengelola KostKu,<br><br><br><br><br>
                <strong>( <?= esc($pembayaran['nama_admin'] ?? 'Pengelola KostKu') ?> )</strong>
            </td>
        </tr>
    </table>

</body>
</html>
