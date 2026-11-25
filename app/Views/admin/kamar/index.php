<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-door-open mr-1 text-primary"></i> Data Kamar Kos
        </h3>
        <div class="card-tools ml-auto">
            <a href="<?= base_url('admin/kamar/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle mr-1"></i> Tambah Kamar Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable-standard">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Foto</th>
                        <th>No. Kamar</th>
                        <th>Lantai</th>
                        <th>Tipe Kamar</th>
                        <th>Harga / Bln</th>
                        <th>Status</th>
                        <th>Penghuni Saat Ini</th>
                        <th width="12%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($kamar as $k) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center" width="80">
                                <?php if (! empty($k['foto']) && file_exists(FCPATH . 'uploads/kamar/' . $k['foto'])) : ?>
                                    <img src="<?= base_url('uploads/kamar/' . $k['foto']) ?>" alt="Kamar" class="img-thumbnail rounded" style="width: 60px; height: 45px; object-fit: cover;">
                                <?php else : ?>
                                    <span class="badge badge-light border text-muted"><i class="fas fa-image"></i> Kosong</span>
                                <?php endif; ?>
                            </td>
                            <td><strong class="font-weight-bold h6 text-dark"><?= esc($k['nomor_kamar']) ?></strong></td>
                            <td><span class="badge badge-secondary"><?= esc($k['lantai']) ?></span></td>
                            <td><?= esc($k['nama_tipe']) ?></td>
                            <td class="font-weight-bold text-success">
                                Rp <?= number_format($k['harga_bulanan'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                                <?php if ($k['status'] === 'Tersedia') : ?>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Tersedia</span>
                                <?php elseif ($k['status'] === 'Terisi') : ?>
                                    <span class="badge badge-primary px-2 py-1"><i class="fas fa-user-lock mr-1"></i> Terisi</span>
                                <?php else : ?>
                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-tools mr-1"></i> Perbaikan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($k['penghuni_aktif'] !== '-') : ?>
                                    <strong class="text-primary"><i class="fas fa-user mr-1"></i> <?= esc($k['penghuni_aktif']) ?></strong>
                                <?php else : ?>
                                    <span class="text-muted italic">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/kamar/edit/' . $k['id']) ?>" class="btn btn-warning btn-sm" title="Edit Kamar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= base_url('admin/kamar/delete/' . $k['id']) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-confirm" data-message="Hapus kamar <?= esc($k['nomor_kamar']) ?>? Pastikan tidak ada penghuni aktif." title="Hapus Kamar">
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
