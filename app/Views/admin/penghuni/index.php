<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-user-friends mr-1 text-primary"></i> Data Penghuni Kost & Kontrakan
        </h3>
        <div class="card-tools ml-auto">
            <a href="<?= base_url('admin/penghuni/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus mr-1"></i> Daftarkan Penghuni Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable-standard">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama & NIK</th>
                        <th>Kontak (WA)</th>
                        <th>Kamar Ditempati</th>
                        <th>Pekerjaan</th>
                        <th>Tgl Masuk / Keluar</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($penghuni as $p) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <strong class="font-weight-bold text-dark"><?= esc($p['nama_lengkap']) ?></strong>
                                <small class="d-block text-muted">NIK: <?= esc($p['nik']) ?></small>
                                <small class="text-primary"><i class="fas fa-envelope mr-1"></i><?= esc($p['email']) ?></small>
                            </td>
                            <td>
                                <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $p['no_hp'])) ?>" target="_blank" class="btn btn-xs btn-outline-success">
                                    <i class="fab fa-whatsapp mr-1"></i> <?= esc($p['no_hp']) ?>
                                </a>
                            </td>
                            <td>
                                <?php if (! empty($p['nomor_kamar'])) : ?>
                                    <span class="badge badge-primary font-weight-bold"><?= esc($p['nomor_kamar']) ?></span>
                                    <small class="d-block text-muted"><?= esc($p['nama_tipe']) ?> (<?= esc($p['lantai']) ?>)</small>
                                <?php else : ?>
                                    <span class="badge badge-secondary">Belum ada kamar</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($p['pekerjaan']) ?></td>
                            <td class="text-sm">
                                <div><i class="fas fa-sign-in-alt text-success mr-1"></i> <?= date('d/m/Y', strtotime($p['tgl_masuk'])) ?></div>
                                <?php if (! empty($p['tgl_keluar'])) : ?>
                                    <div class="text-danger"><i class="fas fa-sign-out-alt text-danger mr-1"></i> <?= date('d/m/Y', strtotime($p['tgl_keluar'])) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($p['status'] === 'Aktif') : ?>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                                <?php else : ?>
                                    <span class="badge badge-secondary px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($p['status'] === 'Aktif') : ?>
                                    <!-- Tombol Checkout -->
                                    <form action="<?= base_url('admin/penghuni/checkout/' . $p['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="button" class="btn btn-info btn-sm btn-checkout-confirm" data-nama="<?= esc($p['nama_lengkap']) ?>" title="Check-out Penghuni">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <a href="<?= base_url('admin/penghuni/edit/' . $p['id']) ?>" class="btn btn-warning btn-sm" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="<?= base_url('admin/penghuni/delete/' . $p['id']) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-confirm" data-message="Hapus data penghuni <?= esc($p['nama_lengkap']) ?> beserta akun loginnya?" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
