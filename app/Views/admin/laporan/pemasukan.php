<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Filter Box -->
<div class="card card-outline card-primary shadow-sm mb-4">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-filter mr-1 text-primary"></i> Filter Periode Laporan Keuangan
        </h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/laporan/pemasukan') ?>" method="get" class="row align-items-end">
            <?php 
            $indonesianMonths = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            ?>
            <div class="col-md-4 form-group mb-md-0">
                <label class="font-weight-bold">Bulan:</label>
                <select name="bulan" class="form-control">
                    <option value="">-- Semua Bulan --</option>
                    <?php for ($m = 1; $m <= 12; $m++) : ?>
                        <option value="<?= $m ?>" <?= (string)$bulan === (string)$m ? 'selected' : '' ?>>
                            <?= $indonesianMonths[$m] ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3 form-group mb-md-0">
                <label class="font-weight-bold">Tahun:</label>
                <input type="number" name="tahun" class="form-control" placeholder="Contoh: <?= date('Y') ?>" value="<?= esc($tahun) ?>">
            </div>
            <div class="col-md-5 form-group mb-md-0 d-flex gap-2">
                <button type="submit" class="btn btn-primary font-weight-bold mr-2">
                    <i class="fas fa-search mr-1"></i> Tampilkan
                </button>
                <a href="<?= base_url('admin/laporan/pemasukan') ?>" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt mr-1"></i> Reset
                </a>
                <a href="<?= base_url('admin/laporan/pemasukan/pdf?bulan=' . urlencode($bulan ?? '') . '&tahun=' . urlencode($tahun ?? '')) ?>" target="_blank" class="btn btn-danger font-weight-bold ml-auto">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak PDF
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-md-6">
        <div class="info-box shadow-sm mb-4">
            <span class="info-box-icon bg-info"><i class="fas fa-receipt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted">Total Transaksi Masuk</span>
                <span class="info-box-number h3 mb-0"><?= count($transaksi) ?> Transaksi</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-box shadow-sm mb-4">
            <span class="info-box-icon bg-success"><i class="fas fa-hand-holding-usd"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted">Total Pendapatan Terverifikasi</span>
                <span class="info-box-number h3 text-success mb-0">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Table Transaksi -->
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-table mr-1 text-primary"></i> Rincian Pemasukan Kas KostKu
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable-standard">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Tgl Pembayaran</th>
                        <th>Kode Bayar / Tagihan</th>
                        <th>Penghuni & Kamar</th>
                        <th>Tipe Kamar</th>
                        <th>Metode Bayar</th>
                        <th class="text-right">Nominal Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($transaksi as $t) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($t['tgl_bayar'])) ?></td>
                            <td>
                                <strong class="text-dark"><?= esc($t['kode_pembayaran']) ?></strong>
                                <small class="d-block text-muted"><?= esc($t['kode_tagihan']) ?></small>
                            </td>
                            <td>
                                <strong class="text-primary"><?= esc($t['nama_penghuni']) ?></strong>
                                <small class="d-block text-muted">Kamar <?= esc($t['nomor_kamar']) ?></small>
                            </td>
                            <td><?= esc($t['nama_tipe']) ?></td>
                            <td>
                                <span class="badge badge-<?= $t['metode_bayar'] === 'Tunai' ? 'secondary' : 'info' ?>">
                                    <?= esc($t['metode_bayar']) ?>
                                </span>
                            </td>
                            <td class="text-right font-weight-bold text-success">
                                Rp <?= number_format($t['nominal_bayar'], 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="6" class="text-right">TOTAL KESELURUHAN:</td>
                        <td class="text-right text-success h5 font-weight-bold">
                            Rp <?= number_format($totalPemasukan, 0, ',', '.') ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
