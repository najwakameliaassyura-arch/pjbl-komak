<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-cash-register mr-1 text-primary"></i> <?= esc($title) ?>
                </h3>
            </div>
            
            <form action="<?= base_url('admin/pembayaran') ?>" method="post" enctype="multipart/form-data">
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

                    <div class="form-group">
                        <label class="font-weight-bold">Pilih Tagihan yang Dibayar <span class="text-danger">*</span></label>
                        <select name="tagihan_id" id="tagihan_select" class="form-control" required onchange="fillNominal(this)">
                            <option value="">-- Pilih Tagihan Belum Lunas --</option>
                            <?php 
                            $indonesianMonths = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            foreach ($tagihanList as $tl) : 
                                $isSel = ($selectedTagihan && $selectedTagihan['id'] === $tl['id']) ? 'selected' : '';
                            ?>
                                <option value="<?= $tl['id'] ?>" data-nominal="<?= $tl['nominal'] ?>" <?= $isSel ?>>
                                    [<?= esc($tl['kode_tagihan']) ?>] <?= esc($tl['nama_penghuni']) ?> (Kamar <?= esc($tl['nomor_kamar']) ?>) - Periode <?= $indonesianMonths[(int)$tl['bulan']] ?? $tl['bulan'] ?> <?= esc($tl['tahun']) ?> (Rp <?= number_format($tl['nominal'], 0, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_bayar" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nominal yang Dibayarkan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Rp</span>
                                </div>
                                <input type="number" step="1000" name="nominal_bayar" id="nominal_bayar_input" class="form-control" value="<?= $selectedTagihan ? $selectedTagihan['nominal'] : '' ?>" placeholder="Contoh: 750000" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="metode_bayar" class="form-control" required>
                                <option value="Tunai">Tunai / Cash (Diterima Langsung di Kos)</option>
                                <option value="Transfer">Transfer Bank / E-Wallet</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Upload Bukti Pembayaran / Struk</label>
                            <input type="file" name="bukti_bayar" class="form-control-file" accept="image/*">
                            <small class="text-muted">Opsional jika pembayaran tunai.</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Catatan Pembayaran</label>
                        <textarea name="catatan_admin" rows="2" class="form-control" placeholder="Contoh: Diterima lunas tunai di meja pengelola"></textarea>
                    </div>

                    <div class="alert alert-success text-sm mb-0">
                        <i class="fas fa-check-circle mr-1"></i> Pembayaran yang dicatat langsung oleh Admin akan otomatis berstatus <strong>Diterima</strong> dan status tagihan terkait akan otomatis diperbarui menjadi <strong>Lunas</strong>.
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('admin/pembayaran') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Simpan & Lunaskan Tagihan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function fillNominal(select) {
        const selected = select.options[select.selectedIndex];
        const nominal = selected.getAttribute('data-nominal');
        if (nominal) {
            document.getElementById('nominal_bayar_input').value = nominal;
        }
    }
</script>
<?= $this->endSection() ?>
