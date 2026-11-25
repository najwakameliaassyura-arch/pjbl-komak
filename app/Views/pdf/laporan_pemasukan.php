<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #222;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .kop {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .kop h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
            color: #1a365d;
        }
        .kop p {
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            color: #555;
        }
        .title-box {
            text-align: center;
            margin-bottom: 18px;
        }
        .title-box h3 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
        }
        .title-box p {
            margin: 3px 0 0 0;
            font-size: 9pt;
            color: #444;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #94a3b8;
            padding: 6px 8px;
            font-size: 9pt;
        }
        .data-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            color: #1e293b;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-weight-bold { font-weight: bold; }
        .total-row {
            background-color: #e2e8f0;
            font-weight: bold;
        }
        .ttd-table {
            width: 100%;
            margin-top: 30px;
        }
        .ttd-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop">
        <h2>KostKu Management</h2>
        <p>Sistem Informasi Manajemen Kos & Kontrakan Modern Terpadu</p>
        <p>Jl. Terusan Telekomunikasi No. 1219, Bandung | Telp/WA: 0812-3456-7890 | Email: admin@kostku.com</p>
    </div>

    <!-- Judul Laporan -->
    <div class="title-box">
        <h3>LAPORAN PEMASUKAN KEUANGAN KOS</h3>
        <p>
            Periode: 
            <?php 
            $indonesianMonths = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            if (! empty($bulan) && ! empty($tahun)) {
                echo $indonesianMonths[(int)$bulan] . ' ' . $tahun;
            } elseif (! empty($tahun)) {
                echo 'Tahun ' . $tahun;
            } else {
                echo 'Semua Periode Transaksi';
            }
            ?>
        </p>
    </div>

    <!-- Tabel Data Transaksi -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Tgl Bayar</th>
                <th width="15%">No. Transaksi</th>
                <th width="20%">Nama Penghuni</th>
                <th width="15%">Kamar & Tipe</th>
                <th width="12%">Periode</th>
                <th width="10%">Metode</th>
                <th width="12%">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transaksi)) : ?>
                <tr>
                    <td colspan="8" class="text-center" style="padding: 15px;">Tidak ada transaksi pembayaran pada periode ini.</td>
                </tr>
            <?php else : ?>
                <?php $no = 1; foreach ($transaksi as $t) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><?= date('d/m/Y', strtotime($t['tgl_bayar'])) ?></td>
                        <td><?= esc($t['kode_pembayaran']) ?></td>
                        <td><?= esc($t['nama_penghuni']) ?></td>
                        <td>Kamar <?= esc($t['nomor_kamar']) ?> (<?= esc($t['nama_tipe']) ?>)</td>
                        <td class="text-center"><?= $indonesianMonths[(int)$t['bulan']] ?? $t['bulan'] ?> <?= esc($t['tahun']) ?></td>
                        <td class="text-center"><?= esc($t['metode_bayar']) ?></td>
                        <td class="text-right font-weight-bold">
                            <?= number_format($t['nominal_bayar'], 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="text-right">TOTAL PENDAPATAN DITERIMA:</td>
                <td class="text-right">
                    Rp <?= number_format($totalPemasukan, 0, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan Pengelola -->
    <table class="ttd-table">
        <tr>
            <td></td>
            <td>
                Bandung, <?= esc($tglCetak) ?><br>
                Pengelola / Pemilik KostKu,<br><br><br><br><br>
                <strong>( Pengelola KostKu )</strong>
            </td>
        </tr>
    </table>

</body>
</html>
