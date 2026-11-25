<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-tools mr-1 text-primary"></i> <?= esc($title) ?>
                </h3>
            </div>
            
            <form action="<?= base_url('penghuni/keluhan') ?>" method="post" enctype="multipart/form-data">
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

                    <div class="alert alert-info text-sm mb-4">
                        <i class="fas fa-info-circle mr-1"></i> Sampaikan keluhan kerusakan fasilitas kamar Anda (misal: AC bocor/kurang dingin, kran air macet, lampu putus, WiFi lambat). Pengelola kos akan segera menindaklanjuti ke teknisi.
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Judul Keluhan / Masalah <span class="text-danger">*</span></label>
                        <input type="text" name="judul_keluhan" class="form-control" placeholder="Contoh: AC Kamar Meneteskan Air" value="<?= old('judul_keluhan') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Deskripsi Masalah Lebih Lengkap <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_keluhan" rows="4" class="form-control" placeholder="Jelaskan sejak kapan masalah terjadi dan kondisinya saat ini..." required><?= old('deskripsi_keluhan') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Upload Foto Kerusakan (Opsional)</label>
                        <input type="file" name="foto_keluhan" class="form-control-file" accept="image/*">
                        <small class="text-muted">Foto pendukung akan mempermudah teknisi mempersiapkan suku cadang. Format JPG, PNG (Maks 3 MB).</small>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('penghuni/keluhan') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Laporan Keluhan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
