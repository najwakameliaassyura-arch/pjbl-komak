<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-door-open mr-1 text-primary"></i> <?= esc($title) ?>
                </h3>
            </div>
            
            <?php $isEdit = isset($kamar); ?>
            <form action="<?= $isEdit ? base_url('admin/kamar/update/' . $kamar['id']) : base_url('admin/kamar') ?>" method="post" enctype="multipart/form-data">
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

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nomor / Kode Kamar <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_kamar" class="form-control" placeholder="Contoh: K-101" value="<?= old('nomor_kamar', $kamar['nomor_kamar'] ?? '') ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Lokasi Lantai <span class="text-danger">*</span></label>
                            <select name="lantai" class="form-control" required>
                                <?php $curLantai = old('lantai', $kamar['lantai'] ?? 'Lantai 1'); ?>
                                <option value="Lantai 1" <?= $curLantai === 'Lantai 1' ? 'selected' : '' ?>>Lantai 1</option>
                                <option value="Lantai 2" <?= $curLantai === 'Lantai 2' ? 'selected' : '' ?>>Lantai 2</option>
                                <option value="Lantai 3" <?= $curLantai === 'Lantai 3' ? 'selected' : '' ?>>Lantai 3</option>
                                <option value="Lantai 4" <?= $curLantai === 'Lantai 4' ? 'selected' : '' ?>>Lantai 4</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Tipe Kamar <span class="text-danger">*</span></label>
                            <select name="tipe_id" class="form-control" required>
                                <option value="">-- Pilih Tipe Kamar --</option>
                                <?php $curTipe = old('tipe_id', $kamar['tipe_id'] ?? ''); ?>
                                <?php foreach ($tipeKamar as $tk) : ?>
                                    <option value="<?= $tk['id'] ?>" <?= (string)$curTipe === (string)$tk['id'] ? 'selected' : '' ?>>
                                        <?= esc($tk['nama_tipe']) ?> (Rp <?= number_format($tk['harga_bulanan'], 0, ',', '.') ?>/bln)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Status Kamar <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <?php $curStatus = old('status', $kamar['status'] ?? 'Tersedia'); ?>
                                <option value="Tersedia" <?= $curStatus === 'Tersedia' ? 'selected' : '' ?>>Tersedia (Siap Huni)</option>
                                <option value="Terisi" <?= $curStatus === 'Terisi' ? 'selected' : '' ?>>Terisi (Ditempati)</option>
                                <option value="Perbaikan" <?= $curStatus === 'Perbaikan' ? 'selected' : '' ?>>Perbaikan / Maintenance</option>
                            </select>
                            <small class="text-muted">Catatan: Status otomatis menjadi Terisi saat ada pendaftaran penghuni baru.</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Foto Kamar</label>
                        <input type="file" name="foto" class="form-control-file" accept="image/*">
                        <small class="text-muted">Format JPG, JPEG, PNG, atau WebP (Maksimal 2 MB).</small>

                        <?php if ($isEdit && ! empty($kamar['foto']) && file_exists(FCPATH . 'uploads/kamar/' . $kamar['foto'])) : ?>
                            <div class="mt-2">
                                <label class="d-block text-sm text-muted">Foto Saat Ini:</label>
                                <img src="<?= base_url('uploads/kamar/' . $kamar['foto']) ?>" class="img-thumbnail" style="max-height: 120px;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan / Posisi Kamar</label>
                        <textarea name="keterangan" rows="2" class="form-control" placeholder="Contoh: Dekat tangga utama, jendela menghadap taman"><?= old('keterangan', $kamar['keterangan'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('admin/kamar') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Data Kamar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
