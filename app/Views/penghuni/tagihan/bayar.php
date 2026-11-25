<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-success card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-file-upload mr-1 text-success"></i> Konfirmasi & Upload Bukti Pembayaran Tagihan
                </h3>
            </div>
            
            <form action="<?= base_url('penghuni/tagihan/bayar/' . $tagihan['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                                    <li><?= esc($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Rekening Tujuan Pembayaran -->
                    <div class="callout callout-info mb-4">
                        <h5 class="font-weight-bold text-primary mb-2"><i class="fas fa-university mr-2"></i> Rekening Pembayaran Resmi KostKu:</h5>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Bank BCA:</strong><br>
                                <span class="h5 font-weight-bold text-dark">8030-1234-5678</span><br>
                                <small class="text-muted">a.n. Pengelola KostKu</small>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Bank Mandiri:</strong><br>
                                <span class="h5 font-weight-bold text-dark">131-00-9876543-2</span><br>
                                <small class="text-muted">a.n. KostKu Management</small>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Tagihan -->
                    <div class="bg-light p-3 rounded mb-4">
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <span class="text-muted text-sm">Kode Tagihan:</span>
                                <div class="font-weight-bold"><?= esc($tagihan['kode_tagihan']) ?></div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <span class="text-muted text-sm">Kamar:</span>
                                <div class="font-weight-bold">Kamar <?= esc($tagihan['nomor_kamar']) ?> (<?= esc($tagihan['nama_tipe']) ?>)</div>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted text-sm">Batas Jatuh Tempo:</span>
                                <div class="font-weight-bold text-danger"><?= date('d F Y', strtotime($tagihan['jatuh_tempo'])) ?></div>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted text-sm">Total yang Harus Dibayar:</span>
                                <h4 class="font-weight-bold text-success mb-0">Rp <?= number_format($tagihan['nominal'], 0, ',', '.') ?></h4>
                            </div>
                        </div>
                    </div>

                    <h5 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                        <i class="fas fa-receipt mr-1 text-primary"></i> Masukkan Rincian Pembayaran Anda
                    </h5>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Tanggal Pembayaran / Transfer <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_bayar" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="metode_bayar" class="form-control" required>
                                <option value="Transfer">Transfer Bank / E-Wallet (BCA, Mandiri, Gopay, Ovo, DANA, QRIS)</option>
                                <option value="Tunai">Tunai / Titip Pengelola</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Upload Foto / Screenshot Bukti Transfer <span class="text-danger">*</span></label>
                        <input type="file" name="bukti_bayar" class="form-control-file" accept="image/*" required>
                        <small class="text-muted">Pastikan foto struk / bukti m-banking jelas, tidak buram, dan menampilkan tanggal serta nominal transfer. Format JPG, PNG (Maks. 3 MB).</small>
                    </div>

                    <div class="alert alert-warning text-sm">
                        <i class="fas fa-shield-alt mr-1"></i> Setelah bukti dikirimkan, status tagihan Anda akan menjadi <strong>Menunggu Verifikasi</strong>. Pengelola kos akan memverifikasi mutasi rekening Anda dalam waktu 1x24 jam.
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('penghuni/tagihan') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-success font-weight-bold px-4">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
