<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-user-edit mr-1 text-primary"></i> <?= esc($title) ?>
                </h3>
            </div>
            
            <?php $isEdit = isset($penghuni); ?>
            <form action="<?= $isEdit ? base_url('admin/penghuni/update/' . $penghuni['id']) : base_url('admin/penghuni') ?>" method="post" enctype="multipart/form-data">
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

                    <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3">
                        <i class="fas fa-id-card mr-1"></i> 1. Identitas Pribadi Penghuni
                    </h5>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: Budi Santoso" value="<?= old('nama_lengkap', $penghuni['nama_lengkap'] ?? '') ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
                            <input type="text" name="nik" class="form-control" placeholder="16 Digit NIK KTP" value="<?= old('nik', $penghuni['nik'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">No. WhatsApp / HP Aktif <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 081234567890" value="<?= old('no_hp', $penghuni['no_hp'] ?? '') ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Pekerjaan / Instansi / Kampus <span class="text-danger">*</span></label>
                            <input type="text" name="pekerjaan" class="form-control" placeholder="Contoh: Mahasiswa ITB / Software Engineer" value="<?= old('pekerjaan', $penghuni['pekerjaan'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Foto KTP Penghuni</label>
                        <input type="file" name="foto_ktp" class="form-control-file" accept="image/*">
                        <small class="text-muted">Format gambar JPG, JPEG, PNG (Maksimal 2 MB).</small>
                        <?php if ($isEdit && ! empty($penghuni['foto_ktp']) && file_exists(FCPATH . 'uploads/ktp/' . $penghuni['foto_ktp'])) : ?>
                            <div class="mt-2">
                                <span class="text-sm text-muted d-block">Foto KTP Tersimpan:</span>
                                <a href="<?= base_url('uploads/ktp/' . $penghuni['foto_ktp']) ?>" target="_blank" class="btn btn-xs btn-outline-info">
                                    <i class="fas fa-eye mr-1"></i> Lihat Foto KTP
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3 mt-4">
                        <i class="fas fa-door-open mr-1"></i> 2. Penempatan Kamar & Sewa
                    </h5>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Pilih Kamar <span class="text-danger">*</span></label>
                            <select name="kamar_id" class="form-control" required>
                                <option value="">-- Pilih Kamar Tersedia --</option>
                                <?php $curKamar = old('kamar_id', $penghuni['kamar_id'] ?? ''); ?>
                                <?php foreach ($kamarTersedia as $k) : ?>
                                    <option value="<?= $k['id'] ?>" <?= (string)$curKamar === (string)$k['id'] ? 'selected' : '' ?>>
                                        <?= esc($k['nomor_kamar']) ?> - <?= esc($k['nama_tipe']) ?> (<?= esc($k['lantai']) ?> - Rp <?= number_format($k['harga_bulanan'], 0, ',', '.') ?>/bln)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-info"><i class="fas fa-info-circle mr-1"></i> Status kamar terpilih otomatis berubah menjadi 'Terisi'.</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Tanggal Masuk (Check-In) <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_masuk" class="form-control" value="<?= old('tgl_masuk', $penghuni['tgl_masuk'] ?? date('Y-m-d')) ?>" required>
                        </div>
                    </div>

                    <h5 class="text-primary font-weight-bold border-bottom pb-2 mb-3 mt-4">
                        <i class="fas fa-key mr-1"></i> 3. Akun Login Portal Penghuni
                    </h5>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="Contoh: budi99" value="<?= old('username', $penghuni['username'] ?? '') ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Email Login <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Contoh: budi@gmail.com" value="<?= old('email', $penghuni['email'] ?? '') ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="font-weight-bold">Password <?= $isEdit ? '<small class="text-muted">(Kosongkan bila tidak diubah)</small>' : '<span class="text-danger">*</span>' ?></label>
                            <input type="password" name="password" class="form-control" placeholder="<?= $isEdit ? 'Ubah password' : 'Min. 6 Karakter' ?>" <?= $isEdit ? '' : 'required' ?>>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('admin/penghuni') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Data Penghuni
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
