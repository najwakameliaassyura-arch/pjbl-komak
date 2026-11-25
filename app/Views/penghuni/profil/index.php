<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <!-- Profil Data Diri -->
    <div class="col-lg-6 mb-4">
        <div class="card card-primary card-outline shadow-sm h-100">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-id-badge mr-1 text-primary"></i> Data Profil Penghuni
                </h3>
            </div>
            <form action="<?= base_url('penghuni/profil/update') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle fa-5x text-primary mb-2"></i>
                        <h4 class="font-weight-bold mb-0"><?= esc($penghuni['nama_lengkap']) ?></h4>
                        <span class="badge badge-success px-2 py-1">Penyewa Kamar <?= esc($penghuni['nomor_kamar'] ?? '-') ?></span>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= old('nama_lengkap', $penghuni['nama_lengkap']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp" class="form-control" value="<?= old('no_hp', $penghuni['no_hp']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Pekerjaan / Instansi / Kampus <span class="text-danger">*</span></label>
                        <input type="text" name="pekerjaan" class="form-control" value="<?= old('pekerjaan', $penghuni['pekerjaan']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= old('email', $user['email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" class="form-control bg-light" value="<?= esc($penghuni['nik']) ?>" readonly>
                        <small class="text-muted">Untuk perubahan NIK silakan hubungi pengelola kos langsung.</small>
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ubah Password -->
    <div class="col-lg-6 mb-4">
        <div class="card card-warning card-outline shadow-sm h-100">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-lock mr-1 text-warning"></i> Ganti Password Akun
                </h3>
            </div>
            <form action="<?= base_url('penghuni/profil/update-password') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="alert alert-light border text-sm mb-4">
                        <i class="fas fa-shield-alt text-warning mr-1"></i> Gunakan kombinasi password yang kuat untuk menjaga keamanan akun portal Anda.
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" name="password_lama" class="form-control" placeholder="Masukkan password lama Anda" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password_baru" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password baru" required>
                    </div>
                </div>
                <div class="card-footer bg-light text-right">
                    <button type="submit" class="btn btn-warning font-weight-bold px-4">
                        <i class="fas fa-key mr-1"></i> Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
