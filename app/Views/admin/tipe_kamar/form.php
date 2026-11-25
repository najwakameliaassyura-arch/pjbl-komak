<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-edit mr-1 text-primary"></i> <?= esc($title) ?>
                </h3>
            </div>
            
            <?php $isEdit = isset($tipe); ?>
            <form action="<?= $isEdit ? base_url('admin/tipe-kamar/update/' . $tipe['id']) : base_url('admin/tipe-kamar') ?>" method="post">
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
                        <label class="font-weight-bold">Nama Tipe Kamar <span class="text-danger">*</span></label>
                        <input type="text" name="nama_tipe" class="form-control" placeholder="Contoh: Deluxe AC Kamar Mandi Luar" value="<?= old('nama_tipe', $tipe['nama_tipe'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Harga Sewa Bulanan (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold">Rp</span>
                            </div>
                            <input type="number" step="1000" name="harga_bulanan" class="form-control" placeholder="Contoh: 1200000" value="<?= old('harga_bulanan', $tipe['harga_bulanan'] ?? '') ?>" required>
                        </div>
                        <small class="text-muted">Nominal harga sewa per bulan tanpa titik atau koma.</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Fasilitas Kamar <span class="text-danger">*</span></label>
                        <textarea name="fasilitas" rows="3" class="form-control" placeholder="Contoh: Kasur Queen Springbed, AC 1 PK, Lemari 2 Pintu, Meja Kerja, WiFi Cepat, Kamar Mandi Luar Bersih." required><?= old('fasilitas', $tipe['fasilitas'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Deskripsi Tambahan</label>
                        <textarea name="deskripsi" rows="2" class="form-control" placeholder="Keterangan singkat mengenai tipe kamar ini (opsional)"><?= old('deskripsi', $tipe['deskripsi'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('admin/tipe-kamar') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Data Tipe Kamar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
