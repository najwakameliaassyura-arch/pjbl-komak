<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-users-cog mr-1 text-primary"></i> Manajemen Pengguna & Hak Akses (RBAC)
        </h3>
        <div class="card-tools ml-auto">
            <a href="<?= base_url('admin/user/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus mr-1"></i> Tambah Pengguna Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable-standard">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role Akses</th>
                        <th>Status Akun</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($users as $u) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><strong class="text-dark font-weight-bold"><?= esc($u['nama_lengkap']) ?></strong></td>
                            <td><code><?= esc($u['username']) ?></code></td>
                            <td><?= esc($u['email']) ?></td>
                            <td class="text-center">
                                <?php if ($u['role'] === 'admin') : ?>
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-user-shield mr-1"></i> ADMIN (Owner)</span>
                                <?php else : ?>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-user mr-1"></i> PENGHUNI</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($u['is_active']) : ?>
                                    <span class="badge badge-success px-2 py-1">Aktif</span>
                                <?php else : ?>
                                    <span class="badge badge-secondary px-2 py-1">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalReset<?= $u['id'] ?>" title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>

                                <a href="<?= base_url('admin/user/edit/' . $u['id']) ?>" class="btn btn-warning btn-sm" title="Edit Data User">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <?php if ((int)$u['id'] !== (int)session()->get('user_id')) : ?>
                                    <form action="<?= base_url('admin/user/delete/' . $u['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-confirm" data-message="Hapus pengguna <?= esc($u['username']) ?>?" title="Hapus User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Modal Reset Password -->
                                <div class="modal fade" id="modalReset<?= $u['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content text-left">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title font-weight-bold">
                                                    <i class="fas fa-key mr-1"></i> Reset Password: <?= esc($u['username']) ?>
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <form action="<?= base_url('admin/user/reset-password/' . $u['id']) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <div class="modal-body">
                                                    <p class="text-sm text-muted">
                                                        Masukkan password baru untuk pengguna <strong><?= esc($u['nama_lengkap']) ?></strong> (<?= esc($u['email']) ?>).
                                                    </p>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Password Baru <span class="text-danger">*</span></label>
                                                        <input type="password" name="password_baru" class="form-control" placeholder="Minimal 6 karakter" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-info font-weight-bold">
                                                        <i class="fas fa-save mr-1"></i> Simpan Password Baru
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
