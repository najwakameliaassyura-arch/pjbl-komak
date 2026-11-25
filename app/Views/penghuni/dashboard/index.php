<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Welcome Banner -->
<div class="alert alert-primary shadow-sm border-0 d-flex align-items-center mb-4">
    <div class="mr-3">
        <i class="fas fa-smile-beam fa-3x text-warning"></i>
    </div>
    <div>
        <h4 class="alert-heading font-weight-bold mb-1">Halo, <?= esc($penghuni['nama_lengkap']) ?>!</h4>
        <p class="mb-0">Selamat datang di portal penghuni KostKu. Pantau status sewa kamar, tagihan bulanan, dan sampaikan keluhan fasilitas dengan mudah.</p>
    </div>
</div>

<div class="row">
    <!-- Info Kamar Saya -->
    <div class="col-lg-7">
        <div class="card card-primary card-outline shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-door-open mr-1 text-primary"></i> Kamar Yang Sedang Anda Tempati
                </h3>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-5 text-center mb-3 mb-md-0">
                        <?php if (! empty($penghuni['foto_kamar']) && file_exists(FCPATH . 'uploads/kamar/' . $penghuni['foto_kamar'])) : ?>
                            <img src="<?= base_url('uploads/kamar/' . $penghuni['foto_kamar']) ?>" class="img-fluid rounded shadow-sm" style="max-height: 180px; width: 100%; object-fit: cover;">
                        <?php else : ?>
                            <div class="bg-secondary rounded p-4 text-white">
                                <i class="fas fa-bed fa-4x text-warning mb-2"></i>
                                <div class="font-weight-bold">Kamar <?= esc($penghuni['nomor_kamar'] ?? '-') ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-7">
                        <span class="badge badge-success px-2 py-1 mb-2"><i class="fas fa-check-circle mr-1"></i> Aktif Menghuni</span>
                        <h3 class="font-weight-bold text-dark mb-1">Kamar <?= esc($penghuni['nomor_kamar'] ?? '-') ?></h3>
                        <h5 class="text-primary font-weight-bold mb-2"><?= esc($penghuni['nama_tipe'] ?? '-') ?></h5>
                        
                        <div class="h5 font-weight-bold text-success mb-3">
                            Rp <?= number_format($penghuni['harga_bulanan'] ?? 0, 0, ',', '.') ?> <small class="text-muted text-sm font-weight-normal">/ bulan</small>
                        </div>

                        <p class="text-sm text-muted mb-2">
                            <strong>Lokasi:</strong> <?= esc($penghuni['lantai'] ?? 'Lantai 1') ?><br>
                            <strong>Mulai Masuk:</strong> <?= date('d F Y', strtotime($penghuni['tgl_masuk'])) ?>
                        </p>
                    </div>
                </div>

                <hr class="my-3">
                <h6 class="font-weight-bold text-dark"><i class="fas fa-concierge-bell text-primary mr-1"></i> Fasilitas Kamar Anda:</h6>
                <p class="text-sm text-muted mb-0">
                    <?= esc($penghuni['fasilitas'] ?? 'Fasilitas standar kamar kost.') ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Status Tagihan & Pembayaran -->
    <div class="col-lg-5">
        <div class="card card-outline card-success shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-wallet mr-1 text-success"></i> Status Tagihan Bulan Ini
                </h3>
            </div>
            <div class="card-body">
                <?php if ($tagihanBulanIni) : ?>
                    <div class="text-center p-3 mb-3 bg-light rounded">
                        <span class="text-muted text-sm d-block mb-1">Periode Bulan <?= date('F Y') ?></span>
                        <h3 class="font-weight-bold text-dark mb-2">
                            Rp <?= number_format($tagihanBulanIni['nominal'], 0, ',', '.') ?>
                        </h3>
                        <div>
                            <?php if ($tagihanBulanIni['status'] === 'Lunas') : ?>
                                <span class="badge badge-success px-3 py-2 h6 mb-0">
                                    <i class="fas fa-check-circle mr-1"></i> LUNAS
                                </span>
                            <?php elseif ($tagihanBulanIni['status'] === 'Menunggu Verifikasi') : ?>
                                <span class="badge badge-warning px-3 py-2 h6 mb-0">
                                    <i class="fas fa-clock mr-1"></i> MENUNGGU VERIFIKASI ADMIN
                                </span>
                            <?php else : ?>
                                <span class="badge badge-danger px-3 py-2 h6 mb-0">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> BELUM LUNAS
                                </span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Jatuh tempo: <?= date('d M Y', strtotime($tagihanBulanIni['jatuh_tempo'])) ?>
                        </small>
                    </div>

                    <?php if ($tagihanBulanIni['status'] === 'Belum Lunas') : ?>
                        <a href="<?= base_url('penghuni/tagihan/bayar/' . $tagihanBulanIni['id']) ?>" class="btn btn-success btn-block font-weight-bold py-2 shadow-sm">
                            <i class="fas fa-upload mr-1"></i> Bayar & Upload Bukti Sekarang
                        </a>
                    <?php elseif ($tagihanBulanIni['status'] === 'Lunas') : ?>
                        <a href="<?= base_url('penghuni/tagihan/kwitansi/' . $tagihanBulanIni['id']) ?>" target="_blank" class="btn btn-outline-info btn-block font-weight-bold py-2">
                            <i class="fas fa-file-pdf mr-1"></i> Download Kwitansi PDF
                        </a>
                    <?php endif; ?>

                <?php else : ?>
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                        <h5>Tidak Ada Tagihan Aktif Bulan Ini</h5>
                        <p class="text-sm mb-0">Tagihan bulan ini belum diterbitkan oleh pengelola kos.</p>
                    </div>
                <?php endif; ?>

                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-sm text-muted">Total Tunggakan:</span>
                    <strong class="text-danger">Rp <?= number_format($totalTunggakan, 0, ',', '.') ?> (<?= $jumlahBelumLunas ?> Tagihan)</strong>
                </div>
                <div class="mt-2 text-right">
                    <a href="<?= base_url('penghuni/tagihan') ?>" class="text-sm font-weight-bold">
                        Lihat Riwayat Semua Tagihan <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Keluhan Kamar -->
<div class="card card-outline card-secondary shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-tools mr-1 text-secondary"></i> Riwayat Keluhan Fasilitas Kamar
        </h3>
        <div class="card-tools ml-auto">
            <a href="<?= base_url('penghuni/keluhan/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Lapor Kerusakan Baru
            </a>
        </div>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-striped mb-0">
            <thead class="thead-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Judul Keluhan</th>
                    <th>Tanggal Lapor</th>
                    <th>Status Penanganan</th>
                    <th>Respon Pengelola</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($keluhanList)) : ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-thumbs-up fa-2x text-muted mb-2"></i>
                            <div>Kondisi kamar Anda baik! Belum ada laporan keluhan yang diajukan.</div>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php $no = 1; foreach (array_slice($keluhanList, 0, 5) as $kl) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <strong><?= esc($kl['judul_keluhan']) ?></strong>
                            </td>
                            <td><?= date('d/m/Y', strtotime($kl['created_at'])) ?></td>
                            <td>
                                <?php if ($kl['status'] === 'Pending') : ?>
                                    <span class="badge badge-danger">Pending</span>
                                <?php elseif ($kl['status'] === 'Diproses') : ?>
                                    <span class="badge badge-warning">Diproses</span>
                                <?php else : ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= !empty($kl['tanggapan_admin']) ? esc($kl['tanggapan_admin']) : '<span class="text-muted italic text-xs">Menunggu respon...</span>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
