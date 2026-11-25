<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-file-invoice-dollar mr-1 text-primary"></i> Tagihan Kamar & Riwayat Pembayaran
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable-standard">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Tagihan</th>
                        <th>Periode Bulan</th>
                        <th>Kamar</th>
                        <th>Nominal</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th width="18%" class="text-center">Aksi / Cetak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $indonesianMonths = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    foreach ($tagihan as $tg) : 
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><strong class="text-dark"><?= esc($tg['kode_tagihan']) ?></strong></td>
                            <td>
                                <span class="badge badge-light border">
                                    <?= $indonesianMonths[(int)$tg['bulan']] ?? $tg['bulan'] ?> <?= esc($tg['tahun']) ?>
                                </span>
                            </td>
                            <td>Kamar <?= esc($tg['nomor_kamar']) ?></td>
                            <td class="font-weight-bold">Rp <?= number_format($tg['nominal'], 0, ',', '.') ?></td>
                            <td><?= date('d M Y', strtotime($tg['jatuh_tempo'])) ?></td>
                            <td class="text-center">
                                <?php if ($tg['status'] === 'Lunas') : ?>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> LUNAS</span>
                                <?php elseif ($tg['status'] === 'Menunggu Verifikasi') : ?>
                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> MENUNGGU VERIFIKASI</span>
                                <?php else : ?>
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i> BELUM LUNAS</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($tg['status'] === 'Belum Lunas') : ?>
                                    <a href="<?= base_url('penghuni/tagihan/bayar/' . $tg['id']) ?>" class="btn btn-sm btn-success font-weight-bold">
                                        <i class="fas fa-credit-card mr-1"></i> Bayar Sekarang
                                    </a>
                                <?php elseif ($tg['status'] === 'Menunggu Verifikasi') : ?>
                                    <span class="badge badge-secondary p-2">
                                        <i class="fas fa-spinner fa-spin mr-1"></i> Sedang Diverifikasi
                                    </span>
                                <?php else : ?>
                                    <a href="<?= base_url('penghuni/tagihan/kwitansi/' . $tg['id']) ?>" target="_blank" class="btn btn-sm btn-info font-weight-bold">
                                        <i class="fas fa-print mr-1"></i> Cetak Kwitansi PDF
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
