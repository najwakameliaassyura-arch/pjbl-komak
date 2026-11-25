<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
        <div class="info-box shadow-sm mb-3">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-door-closed"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted">Total Kamar</span>
                <span class="info-box-number font-weight-bold h4 mb-0"><?= $totalKamar ?></span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
        <div class="info-box shadow-sm mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted">Tersedia</span>
                <span class="info-box-number font-weight-bold h4 mb-0"><?= $kamarTersedia ?></span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
        <div class="info-box shadow-sm mb-3">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-user-lock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted">Terisi</span>
                <span class="info-box-number font-weight-bold h4 mb-0"><?= $kamarTerisi ?></span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
        <div class="info-box shadow-sm mb-3">
            <span class="info-box-icon bg-warning elevation-1 text-white"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted">Penghuni Aktif</span>
                <span class="info-box-number font-weight-bold h4 mb-0"><?= $totalPenghuni ?></span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
        <div class="info-box shadow-sm mb-3">
            <span class="info-box-icon bg-teal elevation-1 text-white"><i class="fas fa-coins"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted">Pemasukan Bln Ini</span>
                <span class="info-box-number font-weight-bold text-sm mb-0">Rp <?= number_format($pemasukanBulanIni, 0, ',', '.') ?></span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
        <div class="info-box shadow-sm mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted">Total Tunggakan</span>
                <span class="info-box-number font-weight-bold text-sm mb-0">Rp <?= number_format($totalTunggakan, 0, ',', '.') ?></span>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

<!-- Chart Row -->
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline shadow-sm mb-4">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-chart-bar text-primary mr-2"></i> Tren Pemasukan Keuangan (6 Bulan Terakhir)
                    </h3>
                    <a href="<?= base_url('admin/laporan/pemasukan') ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-file-invoice mr-1"></i> Lihat Rekap Lengkap
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="position-relative mb-2">
                    <canvas id="revenueChart" height="130"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-warning card-outline shadow-sm mb-4">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-clock text-warning mr-2"></i> Perlu Verifikasi Pembayaran
                </h3>
                <div class="card-tools">
                    <span class="badge badge-warning"><?= count($tagihanMenungguVerifikasi) ?> Menunggu</span>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($tagihanMenungguVerifikasi)) : ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <div>Semua pembayaran telah diverifikasi!</div>
                    </div>
                <?php else : ?>
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        <?php foreach ($tagihanMenungguVerifikasi as $mv) : ?>
                            <li class="item py-2">
                                <div class="product-info ml-2">
                                    <a href="<?= base_url('admin/pembayaran') ?>" class="product-title font-weight-bold text-dark">
                                        <?= esc($mv['nama_penghuni']) ?> (<?= esc($mv['nomor_kamar']) ?>)
                                        <span class="badge badge-info float-right">Rp <?= number_format($mv['nominal_bayar'], 0, ',', '.') ?></span>
                                    </a>
                                    <span class="product-description text-sm text-muted">
                                        Kode: <?= esc($mv['kode_pembayaran']) ?> &bull; Metode: <?= esc($mv['metode_bayar']) ?>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="card-footer text-center p-2 bg-white">
                        <a href="<?= base_url('admin/pembayaran') ?>" class="btn btn-sm btn-primary btn-block">
                            Buka Halaman Verifikasi Pembayaran <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row">
    <!-- Tagihan Belum Lunas / Jatuh Tempo -->
    <div class="col-lg-6">
        <div class="card card-danger card-outline shadow-sm mb-4">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-calendar-times text-danger mr-2"></i> Tagihan Belum Lunas / Jatuh Tempo
                </h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/tagihan') ?>" class="btn btn-tool text-primary">
                        <i class="fas fa-external-link-alt"></i> Semua Tagihan
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle mb-0">
                    <thead>
                        <tr>
                            <th>Penghuni</th>
                            <th>Kamar</th>
                            <th>Jatuh Tempo</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tagihanJatuhTempo)) : ?>
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Tidak ada tagihan tertunggak saat ini.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($tagihanJatuhTempo as $tj) : ?>
                                <tr>
                                    <td><strong><?= esc($tj['nama_penghuni']) ?></strong></td>
                                    <td><span class="badge badge-light border"><?= esc($tj['nomor_kamar']) ?></span></td>
                                    <td class="text-danger font-weight-bold"><?= date('d M Y', strtotime($tj['jatuh_tempo'])) ?></td>
                                    <td>Rp <?= number_format($tj['nominal'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="<?= base_url('admin/pembayaran/new/' . $tj['id']) ?>" class="btn btn-xs btn-success" title="Input Bayar">
                                            <i class="fas fa-cash-register mr-1"></i> Bayar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Keluhan Terbaru -->
    <div class="col-lg-6">
        <div class="card card-secondary card-outline shadow-sm mb-4">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-tools text-secondary mr-2"></i> Laporan Keluhan Kamar Terbaru
                </h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/keluhan') ?>" class="btn btn-tool text-primary">
                        <i class="fas fa-external-link-alt"></i> Semua Keluhan
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kamar</th>
                            <th>Judul Keluhan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($keluhanTerbaru)) : ?>
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Belum ada keluhan masuk dari penghuni.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($keluhanTerbaru as $kl) : ?>
                                <tr>
                                    <td><span class="badge badge-dark"><?= esc($kl['nomor_kamar']) ?></span></td>
                                    <td><?= esc($kl['judul_keluhan']) ?></td>
                                    <td>
                                        <?php if ($kl['status'] === 'Pending') : ?>
                                            <span class="badge badge-danger">Pending</span>
                                        <?php elseif ($kl['status'] === 'Diproses') : ?>
                                            <span class="badge badge-warning">Diproses</span>
                                        <?php else : ?>
                                            <span class="badge badge-success">Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-sm text-muted"><?= date('d/m/Y', strtotime($kl['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        const labels = <?= $chartLabels ?>;
        const dataValues = <?= $chartData ?>;

        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pemasukan Terverifikasi (Rp)',
                    backgroundColor: '#007bff',
                    borderColor: '#0056b3',
                    borderWidth: 1,
                    borderRadius: 4,
                    data: dataValues
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + Number(context.raw).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000).toLocaleString('id-ID') + 'k';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>
