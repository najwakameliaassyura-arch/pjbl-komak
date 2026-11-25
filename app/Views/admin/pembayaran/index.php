<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-money-check-alt mr-1 text-primary"></i> Riwayat Pembayaran & Verifikasi Transfer
        </h3>
        <div class="card-tools ml-auto">
            <a href="<?= base_url('admin/pembayaran/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Catat Pembayaran Manual (Tunai/Transfer)
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable-standard">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Bayar</th>
                        <th>Tagihan & Periode</th>
                        <th>Penghuni & Kamar</th>
                        <th>Tgl Bayar</th>
                        <th>Nominal</th>
                        <th>Metode & Bukti</th>
                        <th>Status Verifikasi</th>
                        <th width="15%" class="text-center">Aksi / Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $indonesianMonths = [
                        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
                        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                    ];
                    foreach ($pembayaran as $p) : 
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><strong class="font-weight-bold text-dark"><?= esc($p['kode_pembayaran']) ?></strong></td>
                            <td>
                                <div><span class="badge badge-light border"><?= esc($p['kode_tagihan']) ?></span></div>
                                <small class="text-muted"><?= $indonesianMonths[(int)$p['bulan']] ?? $p['bulan'] ?> <?= esc($p['tahun']) ?></small>
                            </td>
                            <td>
                                <strong class="text-primary"><?= esc($p['nama_penghuni']) ?></strong>
                                <small class="d-block text-muted">Kamar <?= esc($p['nomor_kamar']) ?></small>
                            </td>
                            <td><?= date('d/m/Y', strtotime($p['tgl_bayar'])) ?></td>
                            <td class="font-weight-bold text-success">
                                Rp <?= number_format($p['nominal_bayar'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $p['metode_bayar'] === 'Tunai' ? 'secondary' : 'info' ?>">
                                    <?= esc($p['metode_bayar']) ?>
                                </span>
                                <?php if (! empty($p['bukti_bayar']) && file_exists(FCPATH . 'uploads/bukti_bayar/' . $p['bukti_bayar'])) : ?>
                                    <button type="button" class="btn btn-xs btn-outline-primary ml-1" data-toggle="modal" data-target="#modalBukti<?= $p['id'] ?>" title="Lihat Bukti Transfer">
                                        <i class="fas fa-receipt"></i> Bukti
                                    </button>

                                    <!-- Modal Bukti Bayar -->
                                    <div class="modal fade" id="modalBukti<?= $p['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-dark text-white">
                                                    <h5 class="modal-title font-weight-bold">Bukti Transfer: <?= esc($p['kode_pembayaran']) ?></h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body text-center p-3">
                                                    <img src="<?= base_url('uploads/bukti_bayar/' . $p['bukti_bayar']) ?>" class="img-fluid rounded shadow-sm" alt="Bukti Transfer">
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <a href="<?= base_url('uploads/bukti_bayar/' . $p['bukti_bayar']) ?>" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-external-link-alt mr-1"></i> Buka Ukuran Penuh
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($p['status_verifikasi'] === 'Diterima') : ?>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Diterima</span>
                                    <?php if (! empty($p['nama_verifikator'])) : ?>
                                        <small class="d-block text-muted text-xs">Oleh: <?= esc($p['nama_verifikator']) ?></small>
                                    <?php endif; ?>
                                <?php elseif ($p['status_verifikasi'] === 'Menunggu Verifikasi') : ?>
                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Menunggu Verifikasi</span>
                                <?php else : ?>
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                                    <?php if (! empty($p['catatan_admin'])) : ?>
                                        <small class="d-block text-danger text-xs"><?= esc($p['catatan_admin']) ?></small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($p['status_verifikasi'] === 'Menunggu Verifikasi') : ?>
                                    <button type="button" class="btn btn-sm btn-primary font-weight-bold" data-toggle="modal" data-target="#modalVerif<?= $p['id'] ?>">
                                        <i class="fas fa-user-check mr-1"></i> Verifikasi
                                    </button>

                                    <!-- Modal Verifikasi -->
                                    <div class="modal fade" id="modalVerif<?= $p['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content text-left">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title font-weight-bold">
                                                        <i class="fas fa-check-double mr-1"></i> Verifikasi Pembayaran <?= esc($p['kode_pembayaran']) ?>
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <form action="<?= base_url('admin/pembayaran/verifikasi/' . $p['id']) ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <div class="text-muted text-sm">Penghuni:</div>
                                                            <div class="font-weight-bold"><?= esc($p['nama_penghuni']) ?> (Kamar <?= esc($p['nomor_kamar']) ?>)</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="text-muted text-sm">Nominal Bayar:</div>
                                                            <h4 class="text-success font-weight-bold">Rp <?= number_format($p['nominal_bayar'], 0, ',', '.') ?></h4>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Tindakan Verifikasi <span class="text-danger">*</span></label>
                                                            <select name="aksi" class="form-control" required>
                                                                <option value="Diterima">DITERIMA (Tagihan Otomatis Menjadi LUNAS)</option>
                                                                <option value="Ditolak">DITOLAK (Tagihan Kembali Belum Lunas)</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Catatan / Alasan Admin</label>
                                                            <textarea name="catatan_admin" rows="2" class="form-control" placeholder="Opsional (misal: Transfer BCA masuk valid, atau nominal kurang)"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary font-weight-bold">
                                                            <i class="fas fa-save mr-1"></i> Simpan Verifikasi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                <?php elseif ($p['status_verifikasi'] === 'Diterima') : ?>
                                    <a href="<?= base_url('admin/laporan/kwitansi/' . $p['tagihan_id']) ?>" target="_blank" class="btn btn-sm btn-info" title="Cetak Kwitansi PDF">
                                        <i class="fas fa-print mr-1"></i> Kwitansi
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted text-sm italic">Selesai</span>
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
