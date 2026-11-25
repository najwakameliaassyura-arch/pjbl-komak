<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= esc($title) ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5pt;
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
        .summary-box {
            margin-bottom: 15px;
            font-size: 9pt;
        }
        .summary-box span {
            margin-right: 20px;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #94a3b8;
            padding: 5px 7px;
            font-size: 8.5pt;
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
        .badge {
            font-weight: bold;
            padding: 2px 5px;
            font-size: 8pt;
        }
        .status-tersedia { color: #059669; font-weight: bold; }
        .status-terisi { color: #2563eb; font-weight: bold; }
        .status-perbaikan { color: #d97706; font-weight: bold; }
        .ttd-table {
            width: 100%;
            margin-top: 25px;
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
        <h3>REKAPITULASI DATA KAMAR & STATUS PENGHUNI AKTIF</h3>
        <p>Dicetak Pada: <?= esc($tglCetak) ?></p>
    </div>

    <!-- Ringkasan Unit -->
    <div class="summary-box">
        <span>Total Unit Kamar: <?= $totalKamar ?> Unit</span>
        <span style="color: #2563eb;">Unit Terisi: <?= $totalTerisi ?> Kamar</span>
        <span style="color: #059669;">Unit Tersedia: <?= $totalTersedia ?> Kamar</span>
        <span style="color: #d97706;">Dalam Perbaikan: <?= $totalKamar - $totalTerisi - $totalTersedia ?> Kamar</span>
    </div>

    <!-- Tabel Data Kamar & Penghuni -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">No. Kamar</th>
                <th width="10%">Lantai</th>
                <th width="18%">Tipe Kamar</th>
                <th width="12%">Harga Sewa/Bln</th>
                <th width="10%">Status</th>
                <th width="18%">Nama Penghuni</th>
                <th width="18%">No. HP / WA</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($kamar as $k) : ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center font-weight-bold"><?= esc($k['nomor_kamar']) ?></td>
                    <td class="text-center"><?= esc($k['lantai']) ?></td>
                    <td><?= esc($k['nama_tipe']) ?></td>
                    <td class="text-right">Rp <?= number_format($k['harga_bulanan'], 0, ',', '.') ?></td>
                    <td class="text-center">
                        <?php if ($k['status'] === 'Tersedia') : ?>
                            <span class="status-tersedia">TERSEDIA</span>
                        <?php elseif ($k['status'] === 'Terisi') : ?>
                            <span class="status-terisi">TERISI</span>
                        <?php else : ?>
                            <span class="status-perbaikan">PERBAIKAN</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (! empty($k['penghuni'])) : ?>
                            <strong><?= esc($k['penghuni']['nama_lengkap']) ?></strong>
                            <small style="display:block; color:#555;">(Sejak <?= date('d/m/Y', strtotime($k['penghuni']['tgl_masuk'])) ?>)</small>
                        <?php else : ?>
                            <span style="color:#888; font-style:italic;">- Kosong -</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= !empty($k['penghuni']) ? esc($k['penghuni']['no_hp']) : '-' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Tanda Tangan -->
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
