<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-tags mr-1 text-primary"></i> Daftar Tipe Kamar Kost
        </h3>
        <div class="card-tools ml-auto">
            <a href="<?= base_url('admin/tipe-kamar/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle mr-1"></i> Tambah Tipe Kamar
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable-standard">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Tipe</th>
                        <th>Harga Sewa / Bln</th>
                        <th>Fasilitas Kamar</th>
                        <th>Jumlah Kamar</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($tipeKamar as $t) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <strong class="text-dark font-weight-bold"><?= esc($t['nama_tipe']) ?></strong>
                                <?php if (! empty($t['deskripsi'])) : ?>
                                    <small class="d-block text-muted"><?= esc($t['deskripsi']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="font-weight-bold text-success">
                                Rp <?= number_format($t['harga_bulanan'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <span class="text-sm"><?= esc($t['fasilitas']) ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info px-2 py-1"><?= $t['jumlah_kamar'] ?> Kamar</span>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/tipe-kamar/edit/' . $t['id']) ?>" class="btn btn-warning btn-sm" title="Edit Tipe">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= base_url('admin/tipe-kamar/delete/' . $t['id']) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-confirm" data-message="Hapus tipe kamar ini? Pastikan tidak ada kamar yang terhubung." title="Hapus Tipe">
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
