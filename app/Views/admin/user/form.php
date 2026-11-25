<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-user mr-1 text-primary"></i> <?= esc($title) ?>
                </h3>
            </div>
            
            <?php $isEdit = isset($user); ?>
            <form action="<?= $isEdit ? base_url('admin/user/update/' . $user['id']) : base_url('admin/user') ?>" method="post">
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
                        <label class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap User" value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="Username untuk login" value="<?= old('username', $user['username'] ?? '') ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="<?= old('email', $user['email'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Role Hak Akses <span class="text-danger">*</span></label>
                            <select name="role" class="form-control" required>
                                <?php $curRole = old('role', $user['role'] ?? 'admin'); ?>
                                <option value="admin" <?= $curRole === 'admin' ? 'selected' : '' ?>>Admin (Pemilik Kost / Full Access)</option>
                                <option value="penghuni" <?= $curRole === 'penghuni' ? 'selected' : '' ?>>Penghuni (Penyewa Kos)</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Status Akun</label>
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" name="is_active" class="custom-control-input" id="switchActive" value="1" <?= old('is_active', $user['is_active'] ?? 1) ? 'checked' : '' ?>>
                                <label class="custom-control-label font-weight-normal" for="switchActive">Akun Aktif (Dapat Login)</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Password <?= $isEdit ? '<small class="text-muted">(Kosongkan bila tidak ingin mengubah password)</small>' : '<span class="text-danger">*</span>' ?></label>
                        <input type="password" name="password" class="form-control" placeholder="<?= $isEdit ? 'Ketik password baru jika ingin diubah' : 'Minimal 6 karakter' ?>" <?= $isEdit ? '' : 'required' ?>>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('admin/user') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Data Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
