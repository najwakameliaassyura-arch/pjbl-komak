<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-tools mr-1 text-primary"></i> Manajemen Keluhan & Kerusakan Fasilitas Kamar
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable-standard">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kamar & Penghuni</th>
                        <th>Judul & Uraian Keluhan</th>
                        <th>Foto Kerusakan</th>
                        <th>Tgl Lapor</th>
                        <th>Status</th>
                        <th>Tanggapan Pengelola</th>
                        <th width="12%" class="text-center">Aksi Respon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($keluhan as $kl) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <strong class="text-dark font-weight-bold">Kamar <?= esc($kl['nomor_kamar']) ?></strong>
                                <small class="d-block text-primary"><?= esc($kl['nama_penghuni']) ?></small>
                                <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $kl['no_hp'])) ?>" target="_blank" class="text-xs text-success">
                                    <i class="fab fa-whatsapp"></i> <?= esc($kl['no_hp']) ?>
                                </a>
                            </td>
                            <td>
                                <strong class="text-dark d-block"><?= esc($kl['judul_keluhan']) ?></strong>
                                <small class="text-muted"><?= nl2br(esc($kl['deskripsi_keluhan'])) ?></small>
                            </td>
                            <td class="text-center">
                                <?php if (! empty($kl['foto_keluhan']) && file_exists(FCPATH . 'uploads/keluhan/' . $kl['foto_keluhan'])) : ?>
                                    <a href="<?= base_url('uploads/keluhan/' . $kl['foto_keluhan']) ?>" target="_blank">
                                        <img src="<?= base_url('uploads/keluhan/' . $kl['foto_keluhan']) ?>" class="img-thumbnail" style="width: 55px; height: 55px; object-fit: cover;">
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted text-xs italic">Tanpa Foto</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-sm"><?= date('d/m/Y H:i', strtotime($kl['created_at'])) ?></td>
                            <td class="text-center">
                                <?php if ($kl['status'] === 'Pending') : ?>
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-hourglass-start mr-1"></i> Pending</span>
                                <?php elseif ($kl['status'] === 'Diproses') : ?>
                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> Diproses</span>
                                <?php else : ?>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-sm">
                                <?php if (! empty($kl['tanggapan_admin'])) : ?>
                                    <div class="text-dark"><?= esc($kl['tanggapan_admin']) ?></div>
                                <?php else : ?>
                                    <span class="text-muted text-xs italic">Belum ada respon</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#modalRespon<?= $kl['id'] ?>">
                                    <i class="fas fa-reply mr-1"></i> Respon
                                </button>

                                <!-- Modal Respon Keluhan -->
                                <div class="modal fade" id="modalRespon<?= $kl['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content text-left">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title font-weight-bold">
                                                    <i class="fas fa-tools mr-1"></i> Tindak Lanjut Keluhan Kamar <?= esc($kl['nomor_kamar']) ?>
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <form action="<?= base_url('admin/keluhan/update/' . $kl['id']) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="text-muted text-sm mb-0">Keluhan dari <?= esc($kl['nama_penghuni']) ?>:</label>
                                                        <div class="font-weight-bold"><?= esc($kl['judul_keluhan']) ?></div>
                                                        <p class="text-sm text-secondary bg-light p-2 rounded"><?= nl2br(esc($kl['deskripsi_keluhan'])) ?></p>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Update Status Pengerjaan <span class="text-danger">*</span></label>
                                                        <select name="status" class="form-control" required>
                                                            <option value="Pending" <?= $kl['status'] === 'Pending' ? 'selected' : '' ?>>Pending (Menunggu Jadwal)</option>
                                                            <option value="Diproses" <?= $kl['status'] === 'Diproses' ? 'selected' : '' ?>>Diproses (Teknisi Sedang Menangani)</option>
                                                            <option value="Selesai" <?= $kl['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai (Fasilitas Sudah Diperbaiki)</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Tanggapan / Catatan untuk Penghuni</label>
                                                        <textarea name="tanggapan_admin" rows="3" class="form-control" placeholder="Contoh: Teknisi sudah memperbaiki pipa air dan sudah bisa digunakan kembali."><?= esc($kl['tanggapan_admin'] ?? '') ?></textarea>
                                                        <small class="text-muted">Tanggapan ini akan langsung tampil di dashboard aplikasi penghuni.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary font-weight-bold">
                                                        <i class="fas fa-save mr-1"></i> Simpan Status & Respon
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
